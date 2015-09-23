window.onDeleteAuthor =  function(id) {
  window.api.authors.delete(id).done(function() {
    document.location.reload(true);
  });
};

window.onDeletePublisher =  function(id) {
  window.api.publishers.delete(id).done(function() {
    document.location.reload(true);
  });
};

(function(w) {
  function _delete(url) {
    return $.ajax({
      url: url,
      type: 'DELETE',
      error: function() {
        console.warn(arguments);
      }
    });
  }
  var authors =  {
    delete: function(id) {
      return _delete('/api/authors/'+id);
    }
  };
  var publishers =  {
    delete: function(id) {
      return _delete('/api/publishers/'+id);
    }
  };

  return w.api = {
    authors: authors,
    publishers: publishers
  };
})(window)

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
    });
  });
}

connectForm('#addForm');
