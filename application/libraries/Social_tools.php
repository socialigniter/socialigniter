<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Social Tools Library
*
* @package		Social Tools
* @subpackage	Social Tools Library
* @author		Brennan Novak
* @link			http://social-igniter.com
*
* Contains functions that do all the basic extensible 'tools' of Social Igniter 
* This includes Categories, Comments, Content, Ratings, Tags
*/
 
class Social_tools
{
	protected $ci;

	function __construct()
	{
		$this->ci =& get_instance();
				
		// Load Models
		$this->ci->load->model('categories_model');
		$this->ci->load->model('comments/comments_model');
		$this->ci->load->model('locations/locations_model');
		$this->ci->load->model('ratings_model');
		$this->ci->load->model('tags_model');
		$this->ci->load->model('taxonomy_model');

		// Define Variables
		$this->site_id 			= config_item('site_id');
		$this->view_comments	= NULL;
	}
	
	/* Misc Tools */
	function does_user_have_access($user_id, $content_id)
	{
		// Is Super or Admin
		if ($this->ci->session->userdata('user_level_id') <= 2)
		{
			return TRUE;
		}
		
		$content = $this->ci->social_igniter->get_content($content_id);
		
		// Is User Owner
		if ($user_id == $content->user_id)
		{
			return TRUE;
		}
		
		$access = $this->get_content_access($user_id, $content_id);
		
		// Does user have access
		if ($access)
		{
			return TRUE;
		}

		return FALSE;
	}
	

	/* Categories */	
	function get_categories()
	{
		return $this->ci->categories_model->get_categories($this->site_id);
	}
	
	function get_categories_type($module)
	{
		return $this->ci->categories_model->get_categories_type($this->site_id, $module);
	}
	
	function add_category($category_data)
	{
		return $this->ci->categories_model->add_category($this->site_id, $category_data);
	}
	
	
	/* Comments */
	function get_comment($comment_id)
	{
		return $this->ci->comments_model->get_comment($comment_id);
	}
	
	function get_comments($module='all')
	{
		return $this->ci->comments_model->get_comments($this->site_id, $module);
	}

	function get_comments_recent($module, $limit=10)
	{
		return $this->ci->comments_model->get_comments_recent($this->site_id, $module, $limit);
	}

	function get_comment_children($reply_to_id)
	{
		return $this->ci->comments_model->get_comment_children($reply_to_id);
	}

	function get_comments_count()
	{
		return $this->ci->comments_model->get_comments_count($this->site_id);
	}

	function get_comments_content_count($content_id, $approval='Y')
	{
		return $this->ci->comments_model->get_comments_content_count($content_id, $approval);
	}

	function get_comments_new_count()
	{
		return $this->ci->comments_model->get_comments_new_count($this->site_id);
	}
	
	function get_comments_content($content_id)
	{
		return $this->ci->comments_model->get_comments_content($content_id);
	}
	
	function add_comment($comment_data)
	{
		// Add Comment
		$comment = $this->ci->comments_model->add_comment($this->site_id, $comment_data);
		
		if ($comment)
		{			
			// Get Comment
			$comment = $this->get_comment($comment);

			// Update Comments Count
			$this->ci->social_igniter->update_content_comments_count($comment->content_id);		
		}
		
		return $comment;
	}

	function update_comment_viewed($comment_id)
	{
		return $this->ci->comments_model->update_comment_viewed($comment_id);
	}

	function update_comment_approve($comment_id)
	{
		return $this->ci->comments_model->update_comment_approve($comment_id);
	}

	function update_comment_orphaned_children($comment_id)
	{
		$orphaned_children = $this->get_comment_children($comment_id);
	
		if (!$orphaned_children) return FALSE;
	
		foreach ($orphaned_children as $child)
		{
			$this->ci->comments_model->update_comment_reply_to_id($child->comment_id, '0');
		}
	
		return TRUE;
	}

	function delete_comment($comment_id)
	{
		return $this->ci->comments_model->delete_comment($comment_id);
	}


	function render_children_comments($comments, $reply_to_id)
	{
		foreach ($comments as $child)
		{
			if ($reply_to_id == $child->reply_to_id)
			{			
				$this->data['comment'] = $child;
			
				if ($reply_to_id != '0') $this->data['sub'] = 'sub_';
				else					 $this->data['sub'] = '';
			
				$this->data['comment_id']		= $child->comment_id;
				$this->data['comment_text']		= $child->comment;
				$this->data['reply_id']			= $child->comment_id;
				$this->view_comments  	       .= $this->ci->load->view('../modules/comments/views/partials/comments_list', $this->data, true);
				
				// Recursive Call
				$this->render_children_comments($comments, $child->comment_id);
			}	
		}
			
		return $this->view_comments;
	}


	/* Locations */
	function get_location($key, $value)
	{
		return $this->ci->locations_model->get_location($key, $value);
	}

	function get_locations($key, $value)
	{
		return $this->ci->locations_model->get_locations($key, $value);
	}

	function get_locations_near($lat, $long)
	{
		return $this->ci->locations_model->get_locations_near($lat, $long);
	}

	function add_location($location_data, $site_id)
	{
		if (!$site_id) $site_id = $this->site_id;
	
		return $this->ci->locations_model->add_location($location_data, $site_id);
	}


	/* Ratings */
	function get_ratings()
	{
		return $this->ci->ratings_model->get_ratings();
	}
	
	function add_rating()
	{
		return $this->ci->ratings_model->add_rating();
	}
	
	

	/* Tags */
	function get_tag($tag)
	{
		$result = $this->ci->tags_model->get_tag($tag);
		return $result;
	}	

	function get_tags_content($content_id)
	{
		$result = $this->ci->tags_model->get_tags($content_id);
		return $result;
	}
		
	function process_tags($tags_post, $content_id)
	{
		if ($tags_post)
		{
			// Declarations
			$tag_total	= 1;
			$tags_array = array(explode(", ", $tags_post));
				
			foreach ($tags_array[0] as $tag)
			{  	
				// Check for tag existence
				$tag_exists 	= $this->get_tag($tag);
	
				// Insert New Tag			
				if (!$tag_exists)
				{			
					$tag_url	= url_username($tag, 'dash', TRUE);
					$tag_id		= $this->ci->tags_model->add_tag($tag, $tag_url);				
				}
				else
				{
					$tag_id		= $tag_exists->tag_id;
				}
				
				// Insert Link
				$insert_link	= $this->ci->tags_model->add_tags_link($tag_id, $content_id);			
							
				// Check Taxonomy Existence
				$tag_total		= $this->ci->tags_model->get_tag_total($tag);			
				$tag_taxonomy	= $this->ci->taxonomy_model->get_taxonomy($tag_id, 'tag');
					
				if ($tag_taxonomy)
				{
					$update_taxonomy = $this->ci->taxonomy_model->update_taxonomy($tag_taxonomy->taxonomy_id, $tag_total);
				}				
				else
				{
					$insert_taxonomy = $this->ci->taxonomy_model->add_taxonomy($tag_id, 'tag', $tag_total);
				}	
			}
			return TRUE;
		}
	}	
	

}