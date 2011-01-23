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
	protected $categories_view;

	function __construct()
	{
		$this->ci =& get_instance();
				
		// Load Models
		$this->ci->load->model('categories_model');
		$this->ci->load->model('comments_model');
		$this->ci->load->model('ratings_model');
		$this->ci->load->model('tags_model');
		$this->ci->load->model('taxonomy_model');

		// Define Variables
		$this->view_comments = NULL;
	}
	
	/* Access Tools */
	function has_access_to_create($type, $user_id)
	{
		// Is Super or Admin
		if ($this->ci->session->userdata('user_level_id') <= 2)
		{
			return 'A';
		}	
	
	}
	
	function has_access_to_modify($type, $object_id)
	{
		// Is Super or Admin
		if ($this->ci->session->userdata('user_level_id') <= 2)
		{
			return TRUE;
		}
		
		if ($type == 'content')
		{		
			// Is User Owner
			if ($this->ci->session->userdata('user_id') == $this->ci->social_igniter->get_content($object_id)->user_id)
			{
				return TRUE;
			}
			
			// Does User Have Access
			/*
			$access = $this->get_content_access($this->ci->session->userdata('user_id'), $content_id);
			
			if ($access)
			{
				return TRUE;
			}
			*/
		}
		
		if ($type == 'comment')
		{
			// Is User Owner
			if ($this->ci->session->userdata('user_id') == $this->get_comment($object_id)->user_id)
			{
				return TRUE;
			}				
		}

		return FALSE;
	}
	
	/* Categories */	
	function get_categories()
	{
		return $this->ci->categories_model->get_categories(config_item('site_id'));
	}

	function get_category($category_id)
	{
		return $this->ci->categories_model->get_category($category_id);	
	}

	function get_category_contents_count($content_id, $approval='Y')
	{
		return $this->ci->categoryies_model->get_comments_content_count($content_id, $approval);
	}

	function get_categories_view($parameter, $value)
	{
		return $this->ci->categories_model->get_categories_view($parameter, $value);	
	}

	function get_category_default_user($parameter, $value, $user_id, $make=FALSE)
	{
		$category = $this->ci->categories_model->get_category_default_user($parameter, $value, $user_id);
	
		if ((!$category) && ($make))
		{
			$category_data = array(
        		'parent_id'		=> 0,
    			'site_id'		=> config_item('site_id'),		
    			'permission'	=> 'E',
				'module'		=> $this->input->post('module'),
    			'type'			=> $this->input->post('type'),
    			'category'		=> $this->input->post('category'),
    			'category_url'	=> $this->input->post('category_url')
        	);	
		
			$this->add_category($category_data);	
		}
		
		return $category;
	}
	
	function get_categories_dropdown($parameter, $value, $user_id, $user_level_id, $add_label=NULL)
	{
		$categories_query		= $this->get_categories_view($parameter, $value);
		$this->categories_view 	= array(0 => '----select----');
		
		// Recursive Func that build child
		$categories 		= $this->render_children_categories($categories_query, 0);
				
		// Add Category
		if ($user_level_id <= 2)
		{
			if (!$add_label)
			{
				$this->categories_view['add_category'] = '+ Add Category';	
			}
			else
			{
				$this->categories_view['add_category'] = $add_label;
			}	
		}
		
		return $this->categories_view;	
	}
	
	function render_children_categories($categories_query, $parent_id)
	{		
		foreach ($categories_query as $child)
		{
			if ($parent_id == $child->parent_id)
			{
				if ($parent_id != '0') $category_display = ' - '.$child->category;
				else $category_display = $child->category;
			
				$this->categories_view[$child->category_id] = $category_display;

				// Recursive Call
				$this->render_children_categories($categories_query, $child->category_id);
			}
		}
			
		return $this->categories_view;
	}	
	

	// Add Category & Activity
	function add_category($category_data, $activity_data=FALSE)
	{
		$category = $this->ci->categories_model->add_category($category_data);

		if ($category)
		{
			$activity_info = array(
				'site_id'	=> $category->site_id,
				'user_id'	=> $category->user_id,
				'verb'		=> 'post',
				'module'	=> $category->module,
				'type'		=> $category->type
			);		
		
			if (!$activity_data)
			{
				$activity_data = array(
					'title'			=> $category->category,
					'content' 		=> character_limiter(strip_tags($category->description, ''), config_item('home_description_length')),
					'category_id'	=> $category->category_id
				);
			}

			// Permalink
			$activity_data['url'] = base_url().$category->module.'/category/'.$category->category_url;

			// Add Activity
			$this->ci->social_igniter->add_activity($activity_info, $activity_data);		
	
			return $category;	
		}
		
		return FALSE;
	}
		
	function update_category_contents_count($category_id)
	{
		$contents_count = $this->ci->social_igniter->get_content_category_count($category_id);
	
		return $this->ci->categories_model->update_category_contents_count($category_id, $contents_count);
	}

	function update_category_details($category_id, $details)
	{
		return $this->ci->categories_model->update_category_details($category_id, $details);
	}
	
	function update_category($category_id, $category_data)
	{	
		return $this->ci->content_model->update_category($content_id, $category_data);
	}		
	
	
	/* Comments */
	function get_comment($comment_id)
	{
		return $this->ci->comments_model->get_comment($comment_id);
	}
	
	function get_comments($site_id, $owner_id, $module='all')
	{
		return $this->ci->comments_model->get_comments($site_id, $owner_id, $module);
	}

	function get_comments_recent($module=NULL, $limit=10)
	{
		return $this->ci->comments_model->get_comments_recent(config_item('site_id'), $module, $limit);
	}

	function get_comment_children($reply_to_id)
	{
		return $this->ci->comments_model->get_comment_children($reply_to_id);
	}

	function get_comments_count()
	{
		return $this->ci->comments_model->get_comments_count(config_item('site_id'));
	}

	function get_comments_content_count($content_id, $approval='Y')
	{
		return $this->ci->comments_model->get_comments_content_count($content_id, $approval);
	}

	function get_comments_new_count($site_id, $owner_id)
	{
		return $this->ci->comments_model->get_comments_new_count($site_id, $owner_id);
	}
	
	function get_comments_content($content_id)
	{
		return $this->ci->comments_model->get_comments_content($content_id);
	}
	
	function add_comment($comment_data)
	{
		$comment = FALSE;
		
		// Add Comment		
		if ($comment_id = $this->ci->comments_model->add_comment(config_item('site_id'), $comment_data))
		{			
			// Get Comment
			$comment = $this->get_comment($comment_id);

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
				$this->view_comments  	       .= $this->ci->load->view(config_item('site_theme').'/partials/comments_list', $this->data, true);
				
				// Recursive Call
				$this->render_children_comments($comments, $child->comment_id);
			}	
		}
			
		return $this->view_comments;
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