<!-- FOOTER CARGADO -->
<?php
// Page transition elements
if (get_theme_mod('enable_page_transitions', false)) : ?>
    <div aria-hidden="true" class="transition-pannel-bg initial-load"></div>
    <?php if (get_theme_mod('enable_page_transitions_borders', true)) : ?>
        <div aria-hidden="true" class="transition-borders-bg"></div>
    <?php endif; ?>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
