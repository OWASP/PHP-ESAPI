<?php /* Smarty version 2.5.0, created on 2003-04-23 15:36:36
         compiled from tutorial_toc.tpl */ ?>
<?php $this->_load_plugins(array(
array('function', 'assign', 'tutorial_toc.tpl', 4, false),)); ?><?php if (count ( $this->_tpl_vars['toc'] )): ?>
<h1 class="title">Table of Contents</h1>
<ul class="toc">
	<?php echo smarty_function_assign(array('var' => 'lastcontext','value' => 'refsect1'), $this) ; ?>

	<?php if (isset($this->_sections['toc'])) unset($this->_sections['toc']);
$this->_sections['toc']['name'] = 'toc';
$this->_sections['toc']['loop'] = is_array($this->_tpl_vars['toc']) ? count($this->_tpl_vars['toc']) : max(0, (int)$this->_tpl_vars['toc']);
$this->_sections['toc']['show'] = true;
$this->_sections['toc']['max'] = $this->_sections['toc']['loop'];
$this->_sections['toc']['step'] = 1;
$this->_sections['toc']['start'] = $this->_sections['toc']['step'] > 0 ? 0 : $this->_sections['toc']['loop']-1;
if ($this->_sections['toc']['show']) {
    $this->_sections['toc']['total'] = $this->_sections['toc']['loop'];
    if ($this->_sections['toc']['total'] == 0)
        $this->_sections['toc']['show'] = false;
} else
    $this->_sections['toc']['total'] = 0;
if ($this->_sections['toc']['show']):

            for ($this->_sections['toc']['index'] = $this->_sections['toc']['start'], $this->_sections['toc']['iteration'] = 1;
                 $this->_sections['toc']['iteration'] <= $this->_sections['toc']['total'];
                 $this->_sections['toc']['index'] += $this->_sections['toc']['step'], $this->_sections['toc']['iteration']++):
$this->_sections['toc']['rownum'] = $this->_sections['toc']['iteration'];
$this->_sections['toc']['index_prev'] = $this->_sections['toc']['index'] - $this->_sections['toc']['step'];
$this->_sections['toc']['index_next'] = $this->_sections['toc']['index'] + $this->_sections['toc']['step'];
$this->_sections['toc']['first']      = ($this->_sections['toc']['iteration'] == 1);
$this->_sections['toc']['last']       = ($this->_sections['toc']['iteration'] == $this->_sections['toc']['total']);
?>
		
		<?php if ($this->_tpl_vars['toc'][$this->_sections['toc']['index']]['tagname'] != $this->_tpl_vars['lastcontext']): ?>
		  <?php if ($this->_tpl_vars['lastcontext'] == 'refsect1'): ?>
				<ul class="toc">
					<li><?php echo $this->_tpl_vars['toc'][$this->_sections['toc']['index']]['link']; ?>
</li>
			<?php else: ?>
				<?php if ($this->_tpl_vars['lastcontext'] == 'refsect2'): ?>
					<?php if ($this->_tpl_vars['toc'][$this->_sections['toc']['index']]['tagname'] == 'refsect1'): ?>
						</ul>
						<li><?php echo $this->_tpl_vars['toc'][$this->_sections['toc']['index']]['link']; ?>
</li>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['toc'][$this->_sections['toc']['index']]['tagname'] == 'refsect3'): ?>
						<ul class="toc">
							<li><?php echo $this->_tpl_vars['toc'][$this->_sections['toc']['index']]['link']; ?>
</li>
					<?php endif; ?>
				<?php else: ?>
					</ul>
					<li><?php echo $this->_tpl_vars['toc'][$this->_sections['toc']['index']]['link']; ?>
</li>
				<?php endif; ?>
			<?php endif; ?>
			<?php echo smarty_function_assign(array('var' => 'lastcontext','value' => $this->_tpl_vars['toc'][$this->_sections['toc']['index']]['tagname']), $this) ; ?>

		<?php else: ?>
			<li><?php echo $this->_tpl_vars['toc'][$this->_sections['toc']['index']]['link']; ?>
</li>
		<?php endif; ?>
	<?php endfor; endif; ?>
	<?php if ($this->_tpl_vars['lastcontext'] == 'refsect2'): ?>
		</ul>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['lastcontext'] == 'refsect3'): ?>
			</ul>
		</ul>
	<?php endif; ?>
</ul>
<?php endif; ?>