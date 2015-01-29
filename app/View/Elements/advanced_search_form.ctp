<div class="advanced-search">
	<div class="search-results">
		<h2>
		Rozšírené hľadanie
		</h2>
	</div>

<?php 

echo $this->Html->script('galpop/jquery.galpop.js');
echo $this->Html->css('jquery.galpop.css');
echo $this->Html->scriptBlock('
			$(function() {

		    	$(".galpop-info").galpop();
			    
			});
		');

?>

<?php 
	echo $this->Form->create('Article', array('url' => array('action' => 'search', 'page' => 1), 'type' => 'get'));
?>

<fieldset>

<?php
	echo $this->Form->input('advanced', array(
			'type' => 'hidden',
			'value' => 1
	));

	echo $this->Form->input('q', array(
			'label' => __('Hľadaný výraz (min. 3 znaky)', true),
			'value' => isset($this->request->query['q']) ? $this->request->query['q'] : ""
	));

	echo $this->Form->input('category_id', array(
			'type' => 'hidden',
			'id' => 'category-id',
			'value' => isset($this->request->query['category_id']) ? $this->request->query['category_id'] : ""
	));
	
	echo $this->Form->input('category', array(
    		'id' => 'categories-autocompleter',
    		'label' => __('Kategória článku', true),
			'value' => isset($category) ? $category['Category']['name'] : ""
    ));

	echo $this->Form->input('from', array(
    		'label' => __('Publikované od', true),
		'value' => isset($this->request->query['from']) ? $this->request->query['from'] : ""
	));
	echo $this->Form->input('to', array(
    		'label' => __('Publikované do', true),
		'value' => isset($this->request->query['to']) ? $this->request->query['to'] : ""
	));

	echo $this->Form->submit(__('Hľadaj!', true), array('id' => 'search-button'));
?>
</fieldset>
<?php 
	echo $this->Form->end();

	?>
</div>
<script>
	$( document ).ready(function() {
	    $( "#ArticleFrom" ).datepicker(
		   	{
		      defaultDate: "+1w",
		      changeMonth: true,
		      numberOfMonths: 1,
		      dateFormat: 'd.m.yy', 
		      onClose: function( selectedDate ) {
		        $( "#ArticleTo" ).datepicker( "option", "minDate", selectedDate );
		      }
		    },
		    "showAnim", "slideDown"
	    );
	    $( "#ArticleTo" ).datepicker({
		      defaultDate: "+1w",
		      dateFormat: 'd.m.yy', 
		      changeMonth: true,
		      numberOfMonths: 1,
		      onClose: function( selectedDate ) {
		        $( "#ArticleFrom" ).datepicker( "option", "maxDate", selectedDate );
		      }
	    });
	
	    
	
	    $( "#categories-autocompleter" ).autocomplete({
	        minLength: 2,
	        source: '/categories/get_autocomplete_categories',
	        focus: function( event, ui ) {
	          $( "#project" ).val( ui.item.label );
	          return false;
	        },
	        select: function( event, ui ) {
	          $( "#category-id" ).attr('value', ui.item.id );  
	          $( "#categories-autocompleter" ).val( ui.item.name );
	          return false;
	        }
	      })
	      .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
	        return $( "<li>" )
	          .append( "<a><b>" + item.name + "</b><br/>" + item.path + "</a>" )
	          .appendTo( ul );
	      };
	
	      $('#search-button').click(function(){
	    	  	if (($('#ArticleQ').val().length > 0) && ($('#ArticleQ').val().length < 3)){
					$( "#dialog" ).dialog({
						height: 70,
						modal: true
					});
					return false;
				}
				else {
					var category = $('#categories-autocompleter').val();
					if (category.length == 0){
						$( "#category-id" ).attr('value', '');
					}
					$('#ArticleSearchForm').submit();
				}
		  });
	});
  </script>
