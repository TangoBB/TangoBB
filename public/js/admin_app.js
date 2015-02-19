$(function() {
  $('#tooltip').tooltip();
  $('select').selectpicker({style: 'btn-hg btn-primary', menuStyle: 'dropdown-inverse'});
  $('.highlight').each(function(index) {
    $(this).attr('id', 'code-' + index);
    CodeMirror.fromTextArea(document.getElementById('code-' + index), {
      mode: "xml",
      htmlMode: true,
      lineNumbers: true,
      tabMode: "indent",
      theme: "neo",
      styleActiveLine: true,
      matchBrackets: true,
      autoCloseTags: true,
      scrollbarStyle: "overlay",
      extraKeys: {
        "F11": function(cm) {
          cm.setOption("fullScreen", !cm.getOption("fullScreen"));
        },
        "Esc": function(cm) {
          if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
        }
      }
    });
  });
});
