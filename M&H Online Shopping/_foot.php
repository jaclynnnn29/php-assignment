    </main>

    <script>
    // Global handler for data-post buttons (Remove, Clear Cart, etc.)
    $('[data-post]').click(function(e) {
        e.preventDefault();
        let url = $(this).data('post');
        let msg = $(this).data('confirm');
        
        if (!msg || confirm(msg)) {
            let f = $('<form method="post"></form>').attr('action', url).appendTo('body');
            f.submit();
        }
    });
</script>

    <footer>
        Developed by <b> M&H ONLINE SHOPPING</b> &middot;
        Copyrighted &copy; <?= date('Y') ?>
    </footer>
</body>
</html>