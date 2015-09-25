function apiCall(url, method, data) {
  method = method === undefined ? 'GET' : method;
  return new Promise(function(resolve, reject) {
    $.ajax({
      url: url,
      method: method,
      data: data
    })
    .done(resolve)
    .fail(function( jqXHR, textStatus ) {
      reject(jqXHR.responseJSON === undefined ? jqXHR.responseText : jqXHR.responseJSON);
    });
  });
}

function getFields(selector) {
  var form = $(selector);
  var result = {};
  form.serializeArray().forEach(function(i) {
    result[i.name] = i.value;
  });
  return result;
}

function connectForm(selector) {
  $(selector).submit(function(event) {
    event.preventDefault();
    var $form = $(this);
    var url = $form.attr('action');
    var formData = getFields(selector);
    var posting = $.post( url, formData);

    posting.done(function( data ) {
      document.location.reload(true);
    })
    .fail(function() {
      console.warn(arguments);
    });
  });
}

window.showModal = function(title, content) {
  $('#myModal .modal-title').html(title);
  $('#myModal .modal-body').html(content);
  $('#myModal').modal();
};

window.onDelete = function(type, id) {
  apiCall('/api/' + type + 's/'+id, 'DELETE')
  .then(function() {
    document.location.reload(true);
  }, function(e) {
    showModal('Error', e.description);
  });
};

$(function() {
  $('table.sticky-header').stickyTableHeaders({fixedOffset: $('#navigation')});
  connectForm('#addForm');
});
