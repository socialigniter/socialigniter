<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
Social Tools Library

@package		Social Tools
@subpackage	Social Tools Library
@author		Brennan Novak
@link			http://social-igniter.com

Contains functions that do all the basic extensible 'tools' of Social Igniter 
This includes Categories, Comments, Content, Ratings, Tags
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
		$this->ci->load->model('places_model');		
		$this->ci->load->model('ratings_model');
		$this->ci->load->model('relationships_model');
		$this->ci->load->model('tags_model');
		$this->ci->load->model('taxonomy_model');
		$this->ci->load->model('upload_model');

		// Define Variables
		$this->view_comments = NULL;
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
	
	function get_category_title_url($type, $title_url)
	{
		return $this->ci->categories_model->get_category_title_url($type, $title_url);
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
	
	function make_categories_dropdown($parameter, $value, $user_id, $user_level_id, $add_label=NULL)
	{
		$categories_query 		= $this->get_categories_view($parameter, $value);
		$this->categories_view 	= array(0 => '----select----');
		$categories 			= $this->render_categories_children($categories_query, 0);
				
		// Add Category if Admin
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

	function render_categories_children($categories_query, $parent_id)
	{		
		foreach ($categories_query as $child)
		{
			if ($parent_id == $child->parent_id)
			{
				if ($parent_id != '0') $category_display = ' - '.$child->category;
				else $category_display = $child->category;
			
				$this->categories_view[$child->category_id] = $category_display;

				// Recursive Call
				$this->render_categories_children($categories_query, $child->category_id);
			}
		}
			
		return $this->categories_view;
	}
	
	function make_categories_url($categories_query, $category_id=0)
	{
		// Declare Instance null for pages with multiple calls 
		$this->categories_view = NULL;
		$elements_all 	= $this->render_categories_url($categories_query, $category_id);
		$elements_view	= '';
		
		if ($elements_all)
		{
			arsort($elements_all);
		
			foreach ($elements_all as $category_url)
			{
				$elements_view .= $category_url.'/';
			}
		}
		
		return $elements_view;
	}

	function render_categories_url($categories_query, $object_category_id)
	{	
		foreach ($categories_query as $category)
		{
			if ($object_category_id == $category->category_id)
			{			
				$this->categories_view[] = $category->category_url;

				if ($category->parent_id)
				{
					$this->render_categories_url($categories_query, $category->parent_id);
				}
			}
		}
			
		return $this->categories_view;
	}
	
	function make_categories_breadcrumb($categories_query, $category_id=0, $base_url, $seperator)
	{
		// Declare Instance null for pages with multiple calls 
		$this->categories_view = NULL;
		$categories_all 	= $this->render_categories_object($categories_query, $category_id);
		$categories_view	= '';
		$categories_count	= count($categories_all);
		$categories_build 	= 0;
		$category_breadcrumb_url = NULL;
		
		if ($categories_all)
		{
			sort($categories_all);
		
			foreach ($categories_all as $category)
			{
				// Do Seperator
				$categories_build++;
				if ($categories_count == $categories_build) $seperator = '';
				
				// Build URL
				$category_breadcrumb_url .= '/'.$category->category_url;			
								
				$categories_view .= '<a href="'.$base_url.$category_breadcrumb_url.'">'.$category->category.'</a>'.$seperator;
			}
		}
		
		return $categories_view;
	}

	function render_categories_object($categories_query, $object_category_id)
	{	
		foreach ($categories_query as $category)
		{
			if ($object_category_id == $category->category_id)
			{			
				$this->categories_view[] = $category;

				if ($category->parent_id)
				{
					$this->render_categories_object($categories_query, $category->parent_id);
				}
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
	
	function delete_comments_content($content_id)
	{
		$comments = $this->get_comments_content($content_id);
		
		if ($comments)
		{
			foreach ($comments as $comment)
			{
				$this->delete_comment($comment->comment_id);
			}
		}
		
		return TRUE;
	}
	
	function make_comments_section($content_id, $type, $logged_user_id, $logged_user_level_id)
	{
		// Get Comments
		$comments 							= $this->get_comments_content($content_id);
		$comments_count						= $this->get_comments_content_count($content_id);
		
		if ($comments_count)	$comments_title = $comments_count;
		else					$comments_title = 'Write';

		$this->data['content_id']			= $content_id;
		$this->data['comments_title']		= $comments_title;
		$this->data['comments_list'] 		= $this->render_comments_children($comments, '0', $logged_user_id, $logged_user_level_id);

		// Write
		$this->data['comment_name']			= $this->ci->session->flashdata('comment_name');
		$this->data['comment_email']		= $this->ci->session->flashdata('comment_email');
		$this->data['comment_write_text'] 	= $this->ci->session->flashdata('comment_write_text');
		$this->data['reply_to_id']			= $this->ci->session->flashdata('reply_to_id');
		$this->data['comment_type']			= $type;
		$this->data['geo_lat']				= $this->ci->session->flashdata('geo_lat');
		$this->data['geo_long']				= $this->ci->session->flashdata('geo_long');
		$this->data['comment_error']		= $this->ci->session->flashdata('comment_error');

		// ReCAPTCHA Enabled
		if ((config_item('comments_recaptcha') == 'TRUE') && (!$this->ci->social_auth->logged_in()))
		{

	
	    	$this->ci->load->library('recaptcha');
		
			$this->data['recaptcha']		= $this->ci->recaptcha->get_html();
		}
		else
		{
			$this->data['recaptcha']		= '';
		}

		return $this->ci->load->view(config_item('site_theme').'/partials/comments_view', $this->data, true);
	}

	function render_comments_children($comments, $reply_to_id, $user_id, $user_level_id)
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
				$this->data['item_can_modify']	= $this->ci->social_auth->has_access_to_modify('comment', $child, $user_id, $user_level_id);

				$this->view_comments  	       .= $this->ci->load->view(config_item('site_theme').'/partials/comments_list', $this->data, true);
				
				// Recursive Call
				$this->render_comments_children($comments, $child->comment_id, $user_id, $user_level_id);
			}	
		}
			
		return $this->view_comments;
	}
	
	
	/* Places */
	function get_place($parameter, $value)
	{
		return $this->ci->places_model->get_place($parameter, $value);
	}

	function get_places_view($parameter, $value)
	{
		return $this->ci->places_model->get_places_view($parameter, $value);
	}
	
	function add_place($place_data)
	{
		if ($place = $this->ci->places_model->add_place($place_data))
		{			
			return $place;
		}
		else
		{
			return FALSE;
		}
	}
	
	function update_place($place_data)
	{
		return $this->ci->places_model->update_place($place_data);
	}

	/* Ratings */
	function get_ratings()
	{
		return $this->ci->ratings_model->get_ratings();
	}
	
	function get_ratings_likes_user($user_id)
	{
		return $this->ci->ratings_model->get_ratings_likes_user($user_id);
	}
	
	function add_rating()
	{
		return $this->ci->ratings_model->add_rating();
	}
	
	
	/* Relationships */
	function check_relationship_exists($relationship_data)
	{
		return $this->ci->relationships_model->check_relationship_exists($relationship_data);
	}

	function get_relationships_followers($user_id)
	{
		return $this->ci->relationships_model->get_relationships_followers($user_id);
	}
	
	function get_relationships_follows($owner_id)
	{
		return $this->ci->relationships_model->get_relationships_follows($owner_id);
	}
	
	function add_relationship($relationship_data)
	{
		$relationship = $this->ci->relationships_model->add_relationship($relationship_data);
		
		if ($relationship)	
		{
			$user = $this->ci->social_auth->get_user('user_id', $relationship->user_id);
				
			$activity_info = array(
				'site_id'		=> $relationship->site_id,
				'user_id'		=> $relationship->owner_id,
				'verb'			=> $relationship->type,
				'module'		=> $relationship->module,
				'type'			=> $relationship->type,
				'content_id'	=> 0
			);
				
			$activity_data = array(
				'title'		=> $user->name,
				'url'		=> base_url().'profile/'.$user->username
			);	
	
			// Add Activity
			$activity = $this->ci->social_igniter->add_activity($activity_info, $activity_data);		
		}
	
		return $relationship;
	}
	
	function update_relationship($relationship_id, $relationship_data)
	{	
		return $this->ci->relationships_model->update_relationship($relationship_id, $relationship_data);
	}	

	function delete_relationship($relationship_id)
	{	
		return $this->ci->relationships_model->delete_relationship($relationship_id);
	}		

	/* Tags */
	function get_tag($tag)
	{
		return $this->ci->tags_model->get_tag($tag);
	}	
	
	function get_tags()
	{
		return $this->ci->tags_model->get_tags();
	}

	// STILL NEED TO CHANGE 'content_id' to 'object_id'
	function get_tags_object($object_id)
	{
		return $this->ci->tags_model->get_tags_object($object_id);
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
	
	function delete_tag($tag_id)
	{
		return $this->ci->tags_model->delete_tag($tag_id);
	}
	
	function delete_tag_link($tag_link_id)
	{
		return $this->ci->tags_model->delete_tag_link($tag_link_id);
	}
	
	function delete_tag_links_object($object_id)
	{
		$tag_links 	= $this->get_tags_object($object_id);
		$link_count	= count($tag_links);
		$link_build = 0;
		
		foreach ($tag_links as $link)
		{
			if ($this->delete_tag_link($link->tag_link_id))
			{
				$link_build++;
			}
		}
		
		if ($link_count == $link_build)
		{
			return TRUE;
		}
		else
		{
			return FALSE;		
		}
	}
	
	/* Upload */
    function verify_upload($consumer_key, $file_hash, $delete=FALSE)
	{
		$user = $this->ci->social_auth->get_user('consumer_key', $consumer_key);
		
		if (!$user)
		{
			return FALSE;
		}
		else
		{		
			$check_upload	= $this->ci->upload_model->check_upload_hash($consumer_key, $file_hash);
		
			if ($check_upload)
			{
				if ($delete) $this->delete_upload($check_upload->upload_id);
			
				return TRUE;
			}
		}
	
		return FALSE;
	}

    function add_upload($upload_data)
	{
		return $this->ci->upload_model->add_upload($upload_data);
	}
	
    function delete_upload($upload_id)
	{
		return $this->ci->upload_model->delete_upload($upload_id);
	}


}