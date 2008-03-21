<?php /* Smarty version 2.5.0, created on 2003-04-23 15:36:11
         compiled from header.tpl */ ?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="iso-8859-1"<?php echo '?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<!-- template designed by Marco Von Ballmoos  -->
			<title><?php echo $this->_tpl_vars['title']; ?>
</title>
			<link rel="stylesheet" href="<?php echo $this->_tpl_vars['subdir']; ?>
media/stylesheet.css" />
			<?php if ($this->_tpl_vars['top2'] || $this->_tpl_vars['top3']): ?>
			<script src="<?php echo $this->_tpl_vars['subdir']; ?>
media/lib/classTree.js"></script>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['top2']): ?>
			<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['top3'] || $this->_tpl_vars['top2']): ?>
			<script language="javascript" type="text/javascript">
				var imgPlus = new Image();
				var imgMinus = new Image();
				imgPlus.src = "<?php echo $this->_tpl_vars['subdir']; ?>
media/images/plus.png";
				imgMinus.src = "<?php echo $this->_tpl_vars['subdir']; ?>
media/images/minus.png";
				
				function showNode(Node){
							switch(navigator.family){
								case 'nn4':
									// Nav 4.x code fork...
							var oTable = document.layers["span" + Node];
							var oImg = document.layers["img" + Node];
									break;
								case 'ie4':
									// IE 4/5 code fork...
							var oTable = document.all["span" + Node];
							var oImg = document.all["img" + Node];
									break;
								case 'gecko':
									// Standards Compliant code fork...
							var oTable = document.getElementById("span" + Node);
							var oImg = document.getElementById("img" + Node);
									break;
							}
					oImg.src = imgMinus.src;
					oTable.style.display = "block";
				}
				
				function hideNode(Node){
							switch(navigator.family){
								case 'nn4':
									// Nav 4.x code fork...
							var oTable = document.layers["span" + Node];
							var oImg = document.layers["img" + Node];
									break;
								case 'ie4':
									// IE 4/5 code fork...
							var oTable = document.all["span" + Node];
							var oImg = document.all["img" + Node];
									break;
								case 'gecko':
									// Standards Compliant code fork...
							var oTable = document.getElementById("span" + Node);
							var oImg = document.getElementById("img" + Node);
									break;
							}
					oImg.src = imgPlus.src;
					oTable.style.display = "none";
				}
				
				function nodeIsVisible(Node){
							switch(navigator.family){
								case 'nn4':
									// Nav 4.x code fork...
							var oTable = document.layers["span" + Node];
									break;
								case 'ie4':
									// IE 4/5 code fork...
							var oTable = document.all["span" + Node];
									break;
								case 'gecko':
									// Standards Compliant code fork...
							var oTable = document.getElementById("span" + Node);
									break;
							}
					return (oTable && oTable.style.display == "block");
				}
				
				function toggleNodeVisibility(Node){
					if (nodeIsVisible(Node)){
						hideNode(Node);
					}else{
						showNode(Node);
					}
				}
			</script>
			<?php endif; ?>
		</head>
		<body>
			<?php if ($this->_tpl_vars['top3']): ?><div class="page-body"><?php endif; ?>
			