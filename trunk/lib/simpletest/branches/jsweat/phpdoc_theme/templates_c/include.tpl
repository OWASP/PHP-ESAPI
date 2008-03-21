<?php /* Smarty version 2.5.0, created on 2003-04-23 15:37:05
         compiled from include.tpl */ ?>
<?php $this->_load_plugins(array(
array('function', 'cycle', 'include.tpl', 3, false),)); ?><?php if (isset($this->_sections['includes'])) unset($this->_sections['includes']);
$this->_sections['includes']['name'] = 'includes';
$this->_sections['includes']['loop'] = is_array($this->_tpl_vars['includes']) ? count($this->_tpl_vars['includes']) : max(0, (int)$this->_tpl_vars['includes']);
$this->_sections['includes']['show'] = true;
$this->_sections['includes']['max'] = $this->_sections['includes']['loop'];
$this->_sections['includes']['step'] = 1;
$this->_sections['includes']['start'] = $this->_sections['includes']['step'] > 0 ? 0 : $this->_sections['includes']['loop']-1;
if ($this->_sections['includes']['show']) {
    $this->_sections['includes']['total'] = $this->_sections['includes']['loop'];
    if ($this->_sections['includes']['total'] == 0)
        $this->_sections['includes']['show'] = false;
} else
    $this->_sections['includes']['total'] = 0;
if ($this->_sections['includes']['show']):

            for ($this->_sections['includes']['index'] = $this->_sections['includes']['start'], $this->_sections['includes']['iteration'] = 1;
                 $this->_sections['includes']['iteration'] <= $this->_sections['includes']['total'];
                 $this->_sections['includes']['index'] += $this->_sections['includes']['step'], $this->_sections['includes']['iteration']++):
$this->_sections['includes']['rownum'] = $this->_sections['includes']['iteration'];
$this->_sections['includes']['index_prev'] = $this->_sections['includes']['index'] - $this->_sections['includes']['step'];
$this->_sections['includes']['index_next'] = $this->_sections['includes']['index'] + $this->_sections['includes']['step'];
$this->_sections['includes']['first']      = ($this->_sections['includes']['iteration'] == 1);
$this->_sections['includes']['last']       = ($this->_sections['includes']['iteration'] == $this->_sections['includes']['total']);
?>
<a name="<?php echo $this->_tpl_vars['includes'][$this->_sections['includes']['index']]['include_file']; ?>
"><!-- --></a>
<div class="<?php echo smarty_function_cycle(array('values' => "evenrow,oddrow"), $this) ; ?>
">
	
	<div>
		<img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/Page.png" alt=" " />
		<span class="include-title">
			<span class="include-type"><?php echo $this->_tpl_vars['includes'][$this->_sections['includes']['index']]['include_name']; ?>
</span>
			(<span class="include-name"><?php echo $this->_tpl_vars['includes'][$this->_sections['includes']['index']]['include_value']; ?>
</span>)
			(line <span class="line-number"><?php if ($this->_tpl_vars['includes'][$this->_sections['includes']['index']]['slink']): ?><?php echo $this->_tpl_vars['includes'][$this->_sections['includes']['index']]['slink']; ?>
<?php else: ?><?php echo $this->_tpl_vars['includes'][$this->_sections['includes']['index']]['line_number']; ?>
<?php endif; ?></span>)
		</span>
	</div>

	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("docblock.tpl", array('sdesc' => $this->_tpl_vars['includes'][$this->_sections['includes']['index']]['sdesc'],'desc' => $this->_tpl_vars['includes'][$this->_sections['includes']['index']]['desc'],'tags' => $this->_tpl_vars['includes'][$this->_sections['includes']['index']]['tags']));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	
</div>
<?php endfor; endif; ?>