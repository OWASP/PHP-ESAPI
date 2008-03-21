<?php /* Smarty version 2.5.0, created on 2003-04-23 15:36:11
         compiled from ric.tpl */ ?>
<?php $this->_load_plugins(array(
array('modifier', 'htmlentities', 'ric.tpl', 4, false),)); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("header.tpl", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div align="center"><h1><?php echo $this->_tpl_vars['name']; ?>
</h1></div>
<pre>
<?php echo $this->_run_mod_handler('htmlentities', true, $this->_tpl_vars['contents']); ?>

</pre>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("footer.tpl", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>