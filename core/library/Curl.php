<?php	
		
	if ( ! defined('ROOT')) exit('No direct script access allowed');

	// =========== 
	// ! Insert record to the database with delay   
	// =========== 
	
	class FURY_Curl{
	
		function FURY_Curl(){
			$this->FURY =& get_instance();
		}
	
		/**
		*
		* Begin a request. 
		*/
		
		function request($url,$handle=false){
		
			/* check the library exists or throw an error */
			
			if($this->_isInstalled()):
			
				if(isset($url) && $url!=''):
				
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					$data = curl_exec($ch);
					curl_close($ch);
					
					$doc = new SimpleXmlElement($data, LIBXML_NOCDATA);
					
					$map = $this->_mapReq($handle);
					
					if($handle && method_exists($this,$map)):						
						
						return $this->$map($doc);
						
					endif;
								
				endif;
			
			else:
				
				throw new Exception(gettext("You do not have the CURL extension installed."));
				
			endif;
		
		}
		
		
		/**
		*
		* Deal with the array as if it came from a wordpress blog feed request 
		*/
		
		function _wordpressFeed($xml){
		
			$posts = array();
			$count = 0;			
			
			if(isset($xml->channel)):
			
				/* We can presume its a RSS FEED */
				
				foreach($xml->channel->item as $v):
				
					$posts[$count] = array(
						"link" => $xml->channel->item[$i]->link,
						"pubDate" => $xml->channel->item[$i]->pubDate,
						"title" => $xml->channel->item[$count]->title,
						"content" => $xml->channel->item[$count]->description
					);
					
					$count++;
					
				endforeach;				
			
			elseif(isset($xml->entry)):
			
				/* We can presume its a ATOM FEED */
								
				if(count($xml->entry)>0):
				
					foreach($xml->entry as $v):
												
						$urlAtt = $v->link[$count]->attributes();
							
						$posts[$count] = array(
							"link" => $urlAtt['href'],
							"comments" => $urlAtt['href'],
							"pubDate" => $v->published,
							"author" => $v->author->name,
							"title" => $v->title,
							"content" => $v->content
						);
						
						$count++;
					
					endforeach;
				
				endif;
			
			endif;
				
			/* Loop round to a limit */
															
			return $posts;

		}
		
		
		/**
		*
		* Check if the CURL extension is installed. 
		*/
		
		function _isInstalled(){
			if(in_array  ('curl', get_loaded_extensions())):
				return true;
			endif;
			return false;
		}
		
		/**
		*
		* Map in a request to the right function 
		*/
		
		function _mapReq($val){
		
			switch($val):
			
				case "wordpress": 	$r = "_wordpressFeed"; 
									break;
			
			endswitch;
		
			return $r;
		
		}

	}