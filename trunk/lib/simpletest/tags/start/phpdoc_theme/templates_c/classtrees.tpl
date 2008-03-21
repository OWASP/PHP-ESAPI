<?php /* Smarty version 2.5.0, created on 2003-04-23 15:36:59
         compiled from classtrees.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("header.tpl", array('top1' => true));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- Start of Class Data -->
<H2>
	<?php echo $this->_smarty_vars['capture']['title']; ?>

</H2>
<?php if (isset($this->_sections['classtrees'])) unset($this->_sections['classtrees']);
$this->_sections['classtrees']['name'] = 'classtrees';
$this->_sections['classtrees']['loop'] = is_array($this->_tpl_vars['classtrees']) ? count($this->_tpl_vars['classtrees']) : max(0, (int)$this->_tpl_vars['classtrees']);
$this->_sections['classtrees']['show'] = true;
$this->_sections['classtrees']['max'] = $this->_sections['classtrees']['loop'];
$this->_sections['classtrees']['step'] = 1;
$this->_sections['classtrees']['start'] = $this->_sections['classtrees']['step'] > 0 ? 0 : $this->_sections['classtrees']['loop']-1;
if ($this->_sections['classtrees']['show']) {
    $this->_sections['classtrees']['total'] = $this->_sections['classtrees']['loop'];
    if ($this->_sections['classtrees']['total'] == 0)
        $this->_sections['classtrees']['show'] = false;
} else
    $this->_sections['classtrees']['total'] = 0;
if ($this->_sections['classtrees']['show']):

            for ($this->_sections['classtrees']['index'] = $this->_sections['classtrees']['start'], $this->_sections['classtrees']['iteration'] = 1;
                 $this->_sections['classtrees']['iteration'] <= $this->_sections['classtrees']['total'];
                 $this->_sections['classtrees']['index'] += $this->_sections['classtrees']['step'], $this->_sections['classtrees']['iteration']++):
$this->_sections['classtrees']['rownum'] = $this->_sections['classtrees']['iteration'];
$this->_sections['classtrees']['index_prev'] = $this->_sections['classtrees']['index'] - $this->_sections['classtrees']['step'];
$this->_sections['classtrees']['index_next'] = $this->_sections['classtrees']['index'] + $this->_sections['classtrees']['step'];
$this->_sections['classtrees']['first']      = ($this->_sections['classtrees']['iteration'] == 1);
$this->_sections['classtrees']['last']       = ($this->_sections['classtrees']['iteration'] == $this->_sections['classtrees']['total']);
?>
<h2>Root class <?php echo $this->_tpl_vars['classtrees'][$this->_sections['classtrees']['index']]['class']; ?>
</h2>
<?php echo $this->_tpl_vars['classtrees'][$this->_sections['classtrees']['index']]['class_tree']; ?>

<?php endfor; endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("footer.tpl", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>