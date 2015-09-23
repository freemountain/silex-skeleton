window.delete = function(id, cb) {
  console.log('delete');
  $.ajax({
    url: '/authors/' + id,
    type: 'DELETE',
    success: cb,
    error: function() {
      console.warn(arguments);
    }
  });
};

window.deleteAndReload =  function(id) {
  window.delete(id, function() {
    document.location.reload(true);
  });
};

$("#addForm").submit(function(event) {

   /* stop form from submitting normally */
   event.preventDefault();

   /* get some values from elements on the page: */
   var $form = $( this );
   var url = $form.attr( 'action' );

   /* Send the data using post */
   var posting = $.post( url, {
     firstName: $('#firstName').val(),
     lastName: $('#lastName').val(),
     contentType:"application/json; charset=utf-8",
     dataType:"json",
   } );

   /* Alerts the results */
   posting.done(function( data ) {
     document.location.reload(true);
   });
 });
