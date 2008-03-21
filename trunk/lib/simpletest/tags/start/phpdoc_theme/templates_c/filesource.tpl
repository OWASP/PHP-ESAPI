<?php /* Smarty version 2.5.0, created on 2003-04-23 15:37:04
         compiled from filesource.tpl */ ?>
<?php ob_start(); ?>File Source for <?php echo $this->_tpl_vars['name']; ?>
<?php $this->_smarty_vars['capture']['tutle'] = ob_get_contents(); ob_end_clean(); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("header.tpl", array('title' => $this->_smarty_vars['capture']['tutle']));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<h1>Source for file <?php echo $this->_tpl_vars['name']; ?>
</h1>
<p>Documentation is available at <?php echo $this->_tpl_vars['docs']; ?>
</p>
<div class="src-code">
<?php echo $this->_tpl_vars['source']; ?>

</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("footer.tpl", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>