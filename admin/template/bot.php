<?php
  global $ADMIN, $TANGO;
?>
</div>
          </div>
      </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo SITE_URL; ?>/public/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo SITE_URL;?>/public/codemirror/lib/codemirror.js"></script>
    <script src="<?php echo SITE_URL;?>/public/codemirror/addon/display/fullscreen.js"></script>
    <script src="<?php echo SITE_URL;?>/public/codemirror/addon/edit/closebrackets.js"></script>
    <script src="<?php echo SITE_URL;?>/public/codemirror/addon/edit/closetags.js"></script>
    <script src="<?php echo SITE_URL;?>/public/codemirror/addon/scroll/simplescrollbars.js"></script>
    <script src="<?php echo SITE_URL;?>/public/codemirror/mode/xml/xml.js"></script>
    <script type="text/javascript">
            $('.highlight').each(function(index) {
                $(this).attr('id', 'code-' + index);
                CodeMirror.fromTextArea(document.getElementById('code-' + index), {
                        mode: "text/xml",
                        lineNumbers: true,
                        tabMode: "indent",
                        theme: "neo",
                        autoCloseTags: true,
                        autoCloseBrackets: true,
                        scrollbarStyle: "simple",
                        extraKeys: {
                          "F11": function(cm) {
                            cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                          },
                          "Esc": function(cm) {
                            if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                          }
                        }
                    }
                );
            });
    </script>
    <script src="<?php echo SITE_URL; ?>/public/js/bootstrap.min.js"></script>
    <script src="<?php echo SITE_URL; ?>/public/js/admin_app.js"></script>
  </body>
</html>
