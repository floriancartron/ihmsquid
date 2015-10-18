/*

UserFrosting
By Alex Weissman

UserFrosting is 100% free and open-source.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the 'Software'), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

$(document).ready(function() {                   
    // Link buttons
    $('.js-blacklist-create').click(function() { 
        blacklistForm('dialog-blacklist-create');
    });
    
    
    $('.js-blacklist-delete').click(function() {
        var btn = $(this);
        var blacklist_id = btn.data('id');
        var name = btn.data('name');
        deleteSalleDialog('dialog-blacklist-delete', blacklist_id, name);
    });	 	
});



/* Display a modal form for updating/creating a blacklist */
// TODO: This function is highly redundant with userForm.  Can we refactor?
function blacklistForm(box_id, bl_id) {	
	bl_id = typeof bl_id !== 'undefined' ? bl_id : "";
	
	// Delete any existing instance of the form with the same name
	if($('#' + box_id).length ) {
		$('#' + box_id).remove();
	}
	
    var data = {
		box_id: box_id,
		render: 'modal'
	};
    
    var url = site['uri']['public'] + "/forms/blacklist";  
    
   
    
	// Fetch and render the form
	$.ajax({  
	  type: "GET",  
	  url: url,
	  data: data,
	  cache: false
	})
	.fail(function(result) {
        // Display errors on failure
        $('#userfrosting-alerts').flashAlerts().done(function() {
        });
	})
	.done(function(result) {
		// Append the form as a modal dialog to the body
		$( "body" ).append(result);
		$('#' + box_id).modal('show');
		
        // Initialize select2's
        $('#' + box_id + ' .select2').select2();
        
		// Initialize is_default
        $('#' + box_id + ' input[name=is_default]:checked').addClass('active');
        
        // Set icon when changed
        $('#' + box_id + ' input[name=icon]').on('change', function(){
            $(this).prev(".icon-preview").find("i").removeClass().addClass($(this).val());
        });
        
		// Link submission buttons
        $("form[name='blacklist']").formValidation({
          framework: 'bootstrap',
          // Feedback icons
          icon: {
              valid: 'fa fa-check',
              invalid: 'fa fa-times',
              validating: 'fa fa-refresh'
          },
          fields: validators
        }).on('success.form.fv', function(e) {
          // Prevent double form submission
          e.preventDefault();
    
          // Get the form instance
          var form = $(e.target);
    
          // Serialize and post to the backend script in ajax mode
          var serializedData = form.find('input, textarea, select').not(':checkbox').serialize();
          // Get non-disabled, unchecked checkbox values, set them to 0
          form.find('input[type=checkbox]:enabled').each(function() {
              if ($(this).is(':checked'))
                  serializedData += "&" + encodeURIComponent(this.name) + "=1";
              else
                  serializedData += "&" + encodeURIComponent(this.name) + "=0";
          });
          // Append page CSRF token
          var csrf_token = $("meta[name=csrf_token]").attr("content");
          serializedData += "&csrf_token=" + encodeURIComponent(csrf_token);
          
          var url = form.attr('action');
          return $.ajax({  
            type: "POST",  
            url: url,  
            data: serializedData       
          }).done(function(data, statusText, jqXHR) {
              // Reload the page
              window.location.reload(true);         
          }).fail(function(jqXHR) {
              if (site['debug'] == true) {
                  document.body.innerHTML = jqXHR.responseText;
              } else {
                  console.log("Error (" + jqXHR.status + "): " + jqXHR.responseText );
              }
              $('#form-alerts').flashAlerts().done(function() {
                  // Re-enable submit button
                  form.data('formValidation').disableSubmitButtons(false);
              });              
          });
        }); 	
	});
}

function deleteSalleDialog(box_id, blacklist_id, name){
	// Delete any existing instance of the form with the same name
	if($('#' + box_id).length ) {
		$('#' + box_id).remove();
	}
	
    var url = site['uri']['public'] + "/forms/confirm";
    
	var data = {
		box_id: box_id,
		box_title: "Suppression du site",
		confirm_message: "Voulez-vous vraiment supprimer le site " + name + " de la liste noire ?",
		confirm_button: "Oui, supprimer le site"
	}
	
	// Generate the form
	$.ajax({  
	  type: "GET",  
	  url: url,
	  data: data
	})
	.fail(function(result) {
        // Display errors on failure
        $('#userfrosting-alerts').flashAlerts().done(function() {
        });
	})
	.done(function(result) {		
		// Append the form as a modal dialog to the body
		$( "body" ).append(result);
		$('#' + box_id).modal('show');        
		$('#' + box_id + ' .js-confirm').click(function(){
            
            var url = site['uri']['public'] + "/addblacklist/b/" + blacklist_id + "/delete";
            
            csrf_token = $("meta[name=csrf_token]").attr("content");
            var data = {
                blacklist_id: blacklist_id,
                csrf_token: csrf_token
            }
            
            $.ajax({  
              type: "POST",  
              url: url,  
              data: data
            }).done(function(result) {
              // Reload the page
              window.location.reload();         
            }).fail(function(jqXHR) {
                if (site['debug'] == true) {
                    document.body.innerHTML = jqXHR.responseText;
                } else {
                    console.log("Error (" + jqXHR.status + "): " + jqXHR.responseText );
                }
                $('#userfrosting-alerts').flashAlerts().done(function() {
                    // Close the dialog
                    $('#' + box_id).modal('hide');
                });              
            });
        });
	});
}

