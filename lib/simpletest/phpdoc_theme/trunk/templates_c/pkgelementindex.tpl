<?php /* Smarty version 2.5.0, created on 2003-04-23 15:36:50
         compiled from pkgelementindex.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("header.tpl", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<a name="top"></a>
<h2>[<?php echo $this->_tpl_vars['package']; ?>
] element index</h2>
<?php if (count ( $this->_tpl_vars['packageindex'] ) > 1): ?>
	<h3>Package indexes</h3>
	<ul>
	<?php if (isset($this->_sections['p'])) unset($this->_sections['p']);
$this->_sections['p']['name'] = 'p';
$this->_sections['p']['loop'] = is_array($this->_tpl_vars['packageindex']) ? count($this->_tpl_vars['packageindex']) : max(0, (int)$this->_tpl_vars['packageindex']);
$this->_sections['p']['show'] = true;
$this->_sections['p']['max'] = $this->_sections['p']['loop'];
$this->_sections['p']['step'] = 1;
$this->_sections['p']['start'] = $this->_sections['p']['step'] > 0 ? 0 : $this->_sections['p']['loop']-1;
if ($this->_sections['p']['show']) {
    $this->_sections['p']['total'] = $this->_sections['p']['loop'];
    if ($this->_sections['p']['total'] == 0)
        $this->_sections['p']['show'] = false;
} else
    $this->_sections['p']['total'] = 0;
if ($this->_sections['p']['show']):

            for ($this->_sections['p']['index'] = $this->_sections['p']['start'], $this->_sections['p']['iteration'] = 1;
                 $this->_sections['p']['iteration'] <= $this->_sections['p']['total'];
                 $this->_sections['p']['index'] += $this->_sections['p']['step'], $this->_sections['p']['iteration']++):
$this->_sections['p']['rownum'] = $this->_sections['p']['iteration'];
$this->_sections['p']['index_prev'] = $this->_sections['p']['index'] - $this->_sections['p']['step'];
$this->_sections['p']['index_next'] = $this->_sections['p']['index'] + $this->_sections['p']['step'];
$this->_sections['p']['first']      = ($this->_sections['p']['iteration'] == 1);
$this->_sections['p']['last']       = ($this->_sections['p']['iteration'] == $this->_sections['p']['total']);
?>
	<?php if ($this->_tpl_vars['packageindex'][$this->_sections['p']['index']]['title'] != $this->_tpl_vars['package']): ?>
		<li><a href="elementindex_<?php echo $this->_tpl_vars['packageindex'][$this->_sections['p']['index']]['title']; ?>
.html"><?php echo $this->_tpl_vars['packageindex'][$this->_sections['p']['index']]['title']; ?>
</a></li>
	<?php endif; ?>
	<?php endfor; endif; ?>
	</ul>
<?php endif; ?>
<a href="elementindex.html">All elements</a>
<br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("basicindex.tpl", array('indexname' => "elementindex_".$this->_tpl_vars['package']));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("footer.tpl", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>