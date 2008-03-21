<?php /* Smarty version 2.5.0, created on 2003-04-23 15:36:18
         compiled from tutorial_nav.tpl */ ?>
<table class="tutorial-nav-box">
	<tr>
		<td style="width: 30%">
			<?php if ($this->_tpl_vars['prev']): ?>
				<a href="<?php echo $this->_tpl_vars['prev']; ?>
"><img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/previous_button.png" alt="Previous"></a>
			<?php else: ?>
				<span class="disabled"><img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/previous_button_disabled.png" alt="Previous"></span>
			<?php endif; ?>
		</td>
		<td style="text-align: center">
			<?php if ($this->_tpl_vars['up']): ?>
				<a href="<?php echo $this->_tpl_vars['up']; ?>
"><img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/up_button.png" alt="Up"></a>
			<?php endif; ?>
		</td>
		<td style="text-align: right; width: 30%">
			<?php if ($this->_tpl_vars['next']): ?>
				<a href="<?php echo $this->_tpl_vars['next']; ?>
"><img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/next_button.png" alt="Next"></a>
			<?php else: ?>
				<span class="disabled"><img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/next_button_disabled.png" alt="Next"></span>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td style="width: 30%">
			<?php if ($this->_tpl_vars['prevtitle']): ?>
				<span class="detail"><?php echo $this->_tpl_vars['prevtitle']; ?>
</span>
			<?php endif; ?>
		</td>
		<td style="text-align: center">
			<?php if ($this->_tpl_vars['uptitle']): ?>
				<span class="detail"><?php echo $this->_tpl_vars['uptitle']; ?>
</span>
			<?php endif; ?>
		</td>
		<td style="text-align: right; width: 30%">
			<?php if ($this->_tpl_vars['nexttitle']): ?>
				<span class="detail"><?php echo $this->_tpl_vars['nexttitle']; ?>
</span>
			<?php endif; ?>
		</td>
	</tr>
</table>
	