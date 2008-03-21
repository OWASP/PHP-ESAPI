<?php /* Smarty version 2.5.0, created on 2003-07-14 23:31:37
         compiled from function.tpl */ ?>
<?php $this->_load_plugins(array(
array('function', 'cycle', 'function.tpl', 3, false),)); ?><?php if (isset($this->_sections['func'])) unset($this->_sections['func']);
$this->_sections['func']['name'] = 'func';
$this->_sections['func']['loop'] = is_array($this->_tpl_vars['functions']) ? count($this->_tpl_vars['functions']) : max(0, (int)$this->_tpl_vars['functions']);
$this->_sections['func']['show'] = true;
$this->_sections['func']['max'] = $this->_sections['func']['loop'];
$this->_sections['func']['step'] = 1;
$this->_sections['func']['start'] = $this->_sections['func']['step'] > 0 ? 0 : $this->_sections['func']['loop']-1;
if ($this->_sections['func']['show']) {
    $this->_sections['func']['total'] = $this->_sections['func']['loop'];
    if ($this->_sections['func']['total'] == 0)
        $this->_sections['func']['show'] = false;
} else
    $this->_sections['func']['total'] = 0;
if ($this->_sections['func']['show']):

            for ($this->_sections['func']['index'] = $this->_sections['func']['start'], $this->_sections['func']['iteration'] = 1;
                 $this->_sections['func']['iteration'] <= $this->_sections['func']['total'];
                 $this->_sections['func']['index'] += $this->_sections['func']['step'], $this->_sections['func']['iteration']++):
$this->_sections['func']['rownum'] = $this->_sections['func']['iteration'];
$this->_sections['func']['index_prev'] = $this->_sections['func']['index'] - $this->_sections['func']['step'];
$this->_sections['func']['index_next'] = $this->_sections['func']['index'] + $this->_sections['func']['step'];
$this->_sections['func']['first']      = ($this->_sections['func']['iteration'] == 1);
$this->_sections['func']['last']       = ($this->_sections['func']['iteration'] == $this->_sections['func']['total']);
?>
<a name="<?php echo $this->_tpl_vars['functions'][$this->_sections['func']['index']]['function_dest']; ?>
" id="<?php echo $this->_tpl_vars['functions'][$this->_sections['func']['index']]['function_dest']; ?>
"><!-- --></a>
<div class="<?php echo smarty_function_cycle(array('values' => "evenrow,oddrow"), $this) ; ?>
">
	
	<div>
		<img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/Function.png" />
		<span class="method-title"><?php echo $this->_tpl_vars['functions'][$this->_sections['func']['index']]['function_name']; ?>
</span> (line <span class="line-number"><?php if ($this->_tpl_vars['functions'][$this->_sections['func']['index']]['slink']): ?><?php echo $this->_tpl_vars['functions'][$this->_sections['func']['index']]['slink']; ?>
<?php else: ?><?php echo $this->_tpl_vars['functions'][$this->_sections['func']['index']]['line_number']; ?>
<?php endif; ?></span>)
	</div> 

	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("docblock.tpl", array('sdesc' => $this->_tpl_vars['functions'][$this->_sections['func']['index']]['sdesc'],'desc' => $this->_tpl_vars['functions'][$this->_sections['func']['index']]['desc'],'tags' => $this->_tpl_vars['functions'][$this->_sections['func']['index']]['tags'],'params' => $this->_tpl_vars['functions'][$this->_sections['func']['index']]['params'],'function' => false));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div class="method-signature">
		<span class="method-result"><?php echo $this->_tpl_vars['functions'][$this->_sections['func']['index']]['function_return']; ?>
</span>
		<span class="method-name">
			<?php if ($this->_tpl_vars['functions'][$this->_sections['func']['index']]['ifunction_call']['returnsref']): ?>&amp;<?php endif; ?><?php echo $this->_tpl_vars['functions'][$this->_sections['func']['index']]['function_name']; ?>

		</span>
		<?php if (count ( $this->_tpl_vars['functions'][$this->_sections['func']['index']]['ifunction_call']['params'] )): ?>
			(<?php if (isset($this->_sections['params'])) unset($this->_sections['params']);
$this->_sections['params']['name'] = 'params';
$this->_sections['params']['loop'] = is_array($this->_tpl_vars['functions'][$this->_sections['func']['index']]['ifunction_call']['params']) ? count($this->_tpl_vars['functions'][$this->_sections['func']['index']]['ifunction_call']['params']) : max(0, (int)$this->_tpl_vars['functions'][$this->_sections['func']['index']]['ifunction_call']['params']);
$this->_sections['params']['show'] = true;
$this->_sections['params']['max'] = $this->_sections['params']['loop'];
$this->_sections['params']['step'] = 1;
$this->_sections['params']['start'] = $this->_sections['params']['step'] > 0 ? 0 : $this->_sections['params']['loop']-1;
if ($this->_sections['params']['show']) {
    $this->_sections['params']['total'] = $this->_sections['params']['loop'];
    if ($this->_sections['params']['total'] == 0)
        $this->_sections['params']['show'] = false;
} else
    $this->_sections['params']['total'] = 0;
if ($this->_sections['params']['show']):

            for ($this->_sections['params']['index'] = $this->_sections['params']['start'], $this->_sections['params']['iteration'] = 1;
                 $this->_sections['params']['iteration'] <= $this->_sections['params']['total'];
                 $this->_sections['params']['index'] += $this->_sections['params']['step'], $this->_sections['params']['iteration']++):
$this->_sections['params']['rownum'] = $this->_sections['params']['iteration'];
$this->_sections['params']['index_prev'] = $this->_sections['params']['index'] - $this->_sections['params']['step'];
$this->_sections['params']['index_next'] = $this->_sections['params']['index'] + $this->_sections['params']['step'];
$this->_sections['params']['first']      = ($this->_sections['params']['iteration'] == 1);
$this->_sections['params']['last']       = ($this->_sections['params']['iteration'] == $this->_sections['params']['total']);
?><?php if ($this->_sections['params']['iteration'] != 1): ?>, <?php endif; ?><?php if ($this->_tpl_vars['functions'][$this->_sections['func']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['hasdefault']): ?>[<?php endif; ?><span class="var-type"><?php echo $this->_tpl_vars['functions'][$this->_sections['func']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['type']; ?>
</span>&nbsp;<span class="var-name"><?php echo $this->_tpl_vars['functions'][$this->_sections['func']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['name']; ?>
</span><?php if ($this->_tpl_vars['functions'][$this->_sections['func']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['hasdefault']): ?> = <span class="var-default"><?php echo $this->_tpl_vars['functions'][$this->_sections['func']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['default']; ?>
</span>]<?php endif; ?><?php endfor; endif; ?>)
		<?php else: ?>
		()
		<?php endif; ?>
	</div>

	<?php if ($this->_tpl_vars['functions'][$this->_sections['func']['index']]['params']): ?>
		<ul class="parameters">
		<?php if (isset($this->_sections['params'])) unset($this->_sections['params']);
$this->_sections['params']['name'] = 'params';
$this->_sections['params']['loop'] = is_array($this->_tpl_vars['functions'][$this->_sections['func']['index']]['params']) ? count($this->_tpl_vars['functions'][$this->_sections['func']['index']]['params']) : max(0, (int)$this->_tpl_vars['functions'][$this->_sections['func']['index']]['params']);
$this->_sections['params']['show'] = true;
$this->_sections['params']['max'] = $this->_sections['params']['loop'];
$this->_sections['params']['step'] = 1;
$this->_sections['params']['start'] = $this->_sections['params']['step'] > 0 ? 0 : $this->_sections['params']['loop']-1;
if ($this->_sections['params']['show']) {
    $this->_sections['params']['total'] = $this->_sections['params']['loop'];
    if ($this->_sections['params']['total'] == 0)
        $this->_sections['params']['show'] = false;
} else
    $this->_sections['params']['total'] = 0;
if ($this->_sections['params']['show']):

            for ($this->_sections['params']['index'] = $this->_sections['params']['start'], $this->_sections['params']['iteration'] = 1;
                 $this->_sections['params']['iteration'] <= $this->_sections['params']['total'];
                 $this->_sections['params']['index'] += $this->_sections['params']['step'], $this->_sections['params']['iteration']++):
$this->_sections['params']['rownum'] = $this->_sections['params']['iteration'];
$this->_sections['params']['index_prev'] = $this->_sections['params']['index'] - $this->_sections['params']['step'];
$this->_sections['params']['index_next'] = $this->_sections['params']['index'] + $this->_sections['params']['step'];
$this->_sections['params']['first']      = ($this->_sections['params']['iteration'] == 1);
$this->_sections['params']['last']       = ($this->_sections['params']['iteration'] == $this->_sections['params']['total']);
?>
			<li>
				<span class="var-type"><?php echo $this->_tpl_vars['functions'][$this->_sections['func']['index']]['params'][$this->_sections['params']['index']]['datatype']; ?>
</span>
				<span class="var-name"><?php echo $this->_tpl_vars['functions'][$this->_sections['func']['index']]['params'][$this->_sections['params']['index']]['var']; ?>
</span><?php if ($this->_tpl_vars['functions'][$this->_sections['func']['index']]['params'][$this->_sections['params']['index']]['data']): ?><span class="var-description">: <?php echo $this->_tpl_vars['functions'][$this->_sections['func']['index']]['params'][$this->_sections['params']['index']]['data']; ?>
</span><?php endif; ?>
			</li>
		<?php endfor; endif; ?>
		</ul>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['functions'][$this->_sections['func']['index']]['function_conflicts']['conflict_type']): ?>
		<hr class="separator" />
		<div><span class="warning">Conflicts with functions:</span><br /> 
			<?php if (isset($this->_sections['me'])) unset($this->_sections['me']);
$this->_sections['me']['name'] = 'me';
$this->_sections['me']['loop'] = is_array($this->_tpl_vars['functions'][$this->_sections['func']['index']]['function_conflicts']['conflicts']) ? count($this->_tpl_vars['functions'][$this->_sections['func']['index']]['function_conflicts']['conflicts']) : max(0, (int)$this->_tpl_vars['functions'][$this->_sections['func']['index']]['function_conflicts']['conflicts']);
$this->_sections['me']['show'] = true;
$this->_sections['me']['max'] = $this->_sections['me']['loop'];
$this->_sections['me']['step'] = 1;
$this->_sections['me']['start'] = $this->_sections['me']['step'] > 0 ? 0 : $this->_sections['me']['loop']-1;
if ($this->_sections['me']['show']) {
    $this->_sections['me']['total'] = $this->_sections['me']['loop'];
    if ($this->_sections['me']['total'] == 0)
        $this->_sections['me']['show'] = false;
} else
    $this->_sections['me']['total'] = 0;
if ($this->_sections['me']['show']):

            for ($this->_sections['me']['index'] = $this->_sections['me']['start'], $this->_sections['me']['iteration'] = 1;
                 $this->_sections['me']['iteration'] <= $this->_sections['me']['total'];
                 $this->_sections['me']['index'] += $this->_sections['me']['step'], $this->_sections['me']['iteration']++):
$this->_sections['me']['rownum'] = $this->_sections['me']['iteration'];
$this->_sections['me']['index_prev'] = $this->_sections['me']['index'] - $this->_sections['me']['step'];
$this->_sections['me']['index_next'] = $this->_sections['me']['index'] + $this->_sections['me']['step'];
$this->_sections['me']['first']      = ($this->_sections['me']['iteration'] == 1);
$this->_sections['me']['last']       = ($this->_sections['me']['iteration'] == $this->_sections['me']['total']);
?>
				<?php echo $this->_tpl_vars['functions'][$this->_sections['func']['index']]['function_conflicts']['conflicts'][$this->_sections['me']['index']]; ?>
<br />
			<?php endfor; endif; ?>
		</div>
	<?php endif; ?>

</div>
<?php endfor; endif; ?>
