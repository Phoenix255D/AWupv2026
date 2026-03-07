<?php
// Obtener la URL base del proyecto automáticamente
$base_url = '/gimnasio/';
?>

<div class="c-sidebar c-sidebar-dark c-sidebar-fixed" id="sidebar">
    <div class="c-sidebar-brand">
        <i class="fas fa-dumbbell me-2"></i>
        <span>Gimnasio</span>
    </div>
    
    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="<?php echo $base_url; ?>index.php">
                <i class="fas fa-tachometer-alt c-sidebar-nav-icon"></i> Dashboard
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="<?php echo $base_url; ?>socios/index.php">
                <i class="fas fa-users c-sidebar-nav-icon"></i> Socios
            </a>
        </li>
    </ul>
</div>