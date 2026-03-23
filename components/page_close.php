<?php
/*
 * Reusable page shell — close section.
 * Usage: include this AFTER any page content.
 *
 * Variable to set before including:
 *   $jsDepth (string) — relative path prefix, e.g. '../' for pages/
 */
$jsDepth = $jsDepth ?? '../';
?>
</div><!-- /.main-content -->
<?php include __DIR__ . '/footer.php'; ?>
<script src="<?php echo $jsDepth; ?>public/js/script.js"></script>
</body>
</html>
