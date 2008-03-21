<?php /* Smarty version 2.5.0, created on 2003-04-23 15:36:57
         compiled from top_frame.tpl */ ?>
<?php $this->_load_plugins(array(
array('function', 'assign', 'top_frame.tpl', 22, false),)); ?><?php echo '<?xml'; ?>
 version="1.0" encoding="iso-8859-1"<?php echo '?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<!-- template designed by Marco Von Ballmoos -->
			<title><?php echo $this->_tpl_vars['title']; ?>
</title>
			<link rel="stylesheet" href="<?php echo $this->_tpl_vars['subdir']; ?>
media/stylesheet.css" />
			<link rel="stylesheet" href="<?php echo $this->_tpl_vars['subdir']; ?>
media/banner.css" />
			<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>
		</head>
		<body>
			<div class="banner">
				<div class="banner-title"><?php echo $this->_tpl_vars['package']; ?>
</div>
				<div class="banner-menu">
					<form>
						<table cellpadding="0" cellspacing="0" style="width: 100%">
							<tr>
								<td>
									<?php if (count ( $this->_tpl_vars['ric'] ) > 1): ?>
										<?php echo smarty_function_assign(array('var' => 'last_ric_name','value' => ""), $this) ; ?>

										<?php if (isset($this->_sections['ric'])) unset($this->_sections['ric']);
$this->_sections['ric']['name'] = 'ric';
$this->_sections['ric']['loop'] = is_array($this->_tpl_vars['ric']) ? count($this->_tpl_vars['ric']) : max(0, (int)$this->_tpl_vars['ric']);
$this->_sections['ric']['show'] = true;
$this->_sections['ric']['max'] = $this->_sections['ric']['loop'];
$this->_sections['ric']['step'] = 1;
$this->_sections['ric']['start'] = $this->_sections['ric']['step'] > 0 ? 0 : $this->_sections['ric']['loop']-1;
if ($this->_sections['ric']['show']) {
    $this->_sections['ric']['total'] = $this->_sections['ric']['loop'];
    if ($this->_sections['ric']['total'] == 0)
        $this->_sections['ric']['show'] = false;
} else
    $this->_sections['ric']['total'] = 0;
if ($this->_sections['ric']['show']):

            for ($this->_sections['ric']['index'] = $this->_sections['ric']['start'], $this->_sections['ric']['iteration'] = 1;
                 $this->_sections['ric']['iteration'] <= $this->_sections['ric']['total'];
                 $this->_sections['ric']['index'] += $this->_sections['ric']['step'], $this->_sections['ric']['iteration']++):
$this->_sections['ric']['rownum'] = $this->_sections['ric']['iteration'];
$this->_sections['ric']['index_prev'] = $this->_sections['ric']['index'] - $this->_sections['ric']['step'];
$this->_sections['ric']['index_next'] = $this->_sections['ric']['index'] + $this->_sections['ric']['step'];
$this->_sections['ric']['first']      = ($this->_sections['ric']['iteration'] == 1);
$this->_sections['ric']['last']       = ($this->_sections['ric']['iteration'] == $this->_sections['ric']['total']);
?>
											<?php if ($this->_tpl_vars['last_ric_name'] != ""): ?> | <?php endif; ?>
											<a href="<?php echo $this->_tpl_vars['ric'][$this->_sections['ric']['index']]['file']; ?>
" target="right"><?php echo $this->_tpl_vars['ric'][$this->_sections['ric']['index']]['name']; ?>
</a>
											<?php echo smarty_function_assign(array('var' => 'last_ric_name','value' => $this->_tpl_vars['ric'][$this->_sections['ric']['index']]['name']), $this) ; ?>

										<?php endfor; endif; ?>
									<?php endif; ?>
								</td>
								<td style="width: 2em">&nbsp;</td>
								<td style="text-align: right">
									<?php if (count ( $this->_tpl_vars['packages'] ) > 1): ?>
										<span class="field">Packages</span> 
										<select class="package-selector" onchange="window.parent.left_bottom.location=this[selectedIndex].value">
										<?php if (isset($this->_sections['p'])) unset($this->_sections['p']);
$this->_sections['p']['name'] = 'p';
$this->_sections['p']['loop'] = is_array($this->_tpl_vars['packages']) ? count($this->_tpl_vars['packages']) : max(0, (int)$this->_tpl_vars['packages']);
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
											<option value="<?php echo $this->_tpl_vars['packages'][$this->_sections['p']['index']]['link']; ?>
"><?php echo $this->_tpl_vars['packages'][$this->_sections['p']['index']]['title']; ?>
</option>
										<?php endfor; endif; ?>
										</select>
									<?php endif; ?>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</body>
	</html>