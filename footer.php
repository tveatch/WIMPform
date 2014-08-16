<div class="container">
<footer>
	<p><span class="footsy">&copy; myDreams; PHP-MySQL 2014</span></p>
</footer>
</div> <!-- /container -->

<!-- Javascript Libraries -->
    <script src="http://yui.yahooapis.com/3.14.1/build/yui/yui.js"></script>
    <script src="scripts/bootstrap.min.js"></script>
    
    <!-- Custom app functionality -->
	<script src="scripts/admin.js"></script>
    
	<script>
    YUI().use('node-base', 'node-event-delegate', function (Y) {
        // This just makes sure that the href="#" attached to the <a> elements
        // don't scroll you back up the page.
        Y.one('body').delegate('click', function (e) {
            e.preventDefault();
        }, 'a[href="#"]');
    });
    </script>
</body>
</html>