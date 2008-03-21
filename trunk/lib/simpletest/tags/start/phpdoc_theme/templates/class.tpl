{include file="header.tpl" top3=true}

<h2 class="class-name"><img src="{$subdir}media/images/{if $abstract}{if $access == 'private'}AbstractPrivate{else}Abstract{/if}{else}{if $access == 'private'}Private{/if}{/if}Class_logo.png"
														alt="{if $abstract}{if $access == 'private'}AbstractPrivate{else}Abstract{/if}{else}{if $access == 'private'}Private{/if}{/if} Class"
														title="{if $abstract}{if $access == 'private'}AbstractPrivate{else}Abstract{/if}{else}{if $access == 'private'}Private{/if}{/if} Class"
														style="vertical-align: middle"> {$class_name}</h2>

<a name="sec-description"></a>
<div class="info-box">
	<div class="info-box-title">Description</div>
	<div class="nav-bar">
		{if $children || $vars || $ivars || $methods || $imethods }
			<span class="disabled">Description</span> |
		{/if}
		{if $children}
			<a href="#sec-descendents">Descendents</a>
			{if $vars || $ivars || $methods || $imethods}|{/if}
		{/if}
		{if $vars || $ivars}
			{if $vars}
				<a href="#sec-var-summary">Vars</a> (<a href="#sec-vars">details</a>)
			{else}
				<a href="#sec-vars">Vars</a>
			{/if}
			{if $methods || $imethods}|{/if}
		{/if}
		{if $methods || $imethods}
			{if $methods}
				<a href="#sec-method-summary">Methods</a> (<a href="#sec-methods">details</a>)
			{else}
				<a href="#sec-methods">Methods</a>
			{/if}			
		{/if}
	</div>
	<div class="info-box-body">
		{include file="docblock.tpl" type="class" sdesc=$sdesc desc=$desc}
		<p class="notes">
			Located in <a class="field" href="{$page_link}">{$source_location}</a> (line <span class="field">{if $class_slink}{$class_slink}{else}{$line_number}{/if}</span>)
		</p>
		
		{if $tutorial}
			<hr class="separator" />
			<div class="notes">Tutorial: <span class="tutorial">{$tutorial}</div>
		{/if}
		
		<pre>{section name=tree loop=$class_tree.classes}{$class_tree.classes[tree]}{$class_tree.distance[tree]}{/section}</pre>
	
		{if $conflicts.conflict_type}
			<hr class="separator" />
			<div><span class="warning">Conflicts with classes:</span><br /> 
			{section name=me loop=$conflicts.conflicts}
				{$conflicts.conflicts[me]}<br />
			{/section}
			</div>
		{/if}
	</div>
</div>

{if $children}
	<a name="sec-descendents"></a>
	<div class="info-box">
		<div class="info-box-title">Direct descendents</div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			<span class="disabled">Descendents</span>
			{if $vars || $ivars || $methods || $imethods}|{/if}
			{if $vars || $ivars}
				{if $vars}
					<a href="#sec-var-summary">Vars</a> (<a href="#sec-vars">details</a>)
				{else}
					<a href="#sec-vars">Vars</a>
				{/if}
				{if $methods || $imethods}|{/if}
			{/if}
			{if $methods || $imethods}
				{if $methods}
					<a href="#sec-method-summary">Methods</a> (<a href="#sec-methods">details</a>)
				{else}
					<a href="#sec-methods">Methods</a>
				{/if}			
			{/if}
		</div>
		<div class="info-box-body">
			<table cellpadding="2" cellspacing="0" class="class-table">
				<tr>
					<th class="class-table-header">Class</th>
					<th class="class-table-header">Description</th>
				</tr>
				{section name=kids loop=$children}
				<tr>
					<td style="padding-right: 2em; white-space: nowrap">
						<img src="{$subdir}media/images/{if $children[kids].abstract}Abstract{/if}{if $children[kids].access == 'private'}Private{/if}Class.png"
								 alt="{if $children[kids].abstract}Abstract{/if}{if $children[kids].access == 'private'}Private{/if} class"
								 title="{if $children[kids].abstract}Abstract{/if}{if $children[kids].access == 'private'}Private{/if} class"
								 style="vertical-align: center"/>
						{$children[kids].link}
					</td>
					<td>
					{if $children[kids].sdesc}
						{$children[kids].sdesc}
					{else}
						{$children[kids].desc}
					{/if}
					</td>
				</tr>
				{/section}
			</table>
		</div>
	</div>
{/if}

{if $vars}
	<a name="sec-var-summary"></a>
	<div class="info-box">
		<div class="info-box-title">Variable Summary</span></div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			{if $children}
				<a href="#sec-descendents">Descendents</a> |
			{/if}
			<span class="disabled">Vars</span> (<a href="#sec-vars">details</a>)
			{if $methods || $imethods}
				| 
				{if $methods}
					<a href="#sec-method-summary">Methods</a> (<a href="#sec-methods">details</a>)
				{else}
					<a href="#sec-methods">Methods</a>
				{/if}			
			{/if}
		</div>
		<div class="info-box-body">
			<div class="var-summary">
				{section name=vars loop=$vars}
				<div class="var-title">
					<img src="{$subdir}media/images/{if $vars[vars].access == 'private'}PrivateVariable{else}Variable{/if}.png" alt=" " />
					<span class="var-type">{$vars[vars].var_type}</span>
					<a href="#{$vars[vars].var_name}" title="details" class="var-name">{$vars[vars].var_name}</a>
				</div>
				{/section}
			</div>
		</div>
	</div>
{/if}

{if $methods}
	<a name="sec-method-summary"></a>
	<div class="info-box">
		<div class="info-box-title">Method Summary</span></div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			{if $children}
				<a href="#sec-descendents">Descendents</a> |
			{/if}
			{if $vars || $ivars}
				{if $vars}
					<a href="#sec-var-summary">Vars</a> (<a href="#sec-vars">details</a>)
				{else}
					<a href="#sec-vars">Vars</a>
				{/if} 
				|
			{/if}
			<span class="disabled">Methods</span> (<a href="#sec-methods">details</a>)
		</div>
		<div class="info-box-body">			
			<div class="method-summary">
				{section name=methods loop=$methods}				
				<div class="method-definition">
					<img src="{$subdir}media/images/{if $methods[methods].ifunction_call.constructor}Constructor{elseif $methods[methods].ifunction_call.destructor}Destructor{elseif $methods[methods].access == 'private'}{if $methods[methods].abstract}Abstract{/if}PrivateMethod{else}{if $methods[methods].abstract}Abstract{/if}Method{/if}.png" alt=" "/>
					{if $methods[methods].function_return}
						<span class="method-result">{$methods[methods].function_return}</span>
					{/if}
					<a href="#{$methods[methods].function_name}" title="details" class="method-name">{if $methods[methods].ifunction_call.returnsref}&amp;{/if}{$methods[methods].function_name}</a>
					{if count($methods[methods].ifunction_call.params)}
						({section name=params loop=$methods[methods].ifunction_call.params}{if $smarty.section.params.iteration != 1}, {/if}{if $methods[methods].ifunction_call.params[params].default}[{/if}<span class="var-type">{$methods[methods].ifunction_call.params[params].type}</span>&nbsp;<span class="var-name">{$methods[methods].ifunction_call.params[params].name}</span>{if $methods[methods].ifunction_call.params[params].default} = <span class="var-default">{$methods[methods].ifunction_call.params[params].default}</span>]{/if}{/section})
					{else}
					()
					{/if}
				</div>
				{/section}
			</div>
		</div>
	</div>		
{/if}

{if $vars || $ivars}
	<a name="sec-vars"></a>
	<div class="info-box">
		<div class="info-box-title">Variables</div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			{if $children}
				<a href="#sec-descendents">Descendents</a> |
			{/if}
			{if $methods}
				<a href="#sec-var-summary">Vars</a> (<span class="disabled">details</span>)
			{else}
				<span class="disabled">Vars</span>
			{/if}			
			
			{if $methods || $imethods}
				| 
				{if $methods}
					<a href="#sec-method-summary">Methods</a> (<a href="#sec-methods">details</a>)
				{else}
					<a href="#sec-methods">Methods</a>
				{/if}			
			{/if}
		</div>
		<div class="info-box-body">
			{include file="var.tpl"}
			{if $ivars}
				<h4>Inherited Variables</h4>
				<A NAME='inherited_vars'><!-- --></A>
				{section name=ivars loop=$ivars}
					<p>Inherited from <span class="classname">{$ivars[ivars].parent_class}</span></p>
					<blockquote>
						{section name=ivars2 loop=$ivars[ivars].ivars}
							<img src="{$subdir}media/images/{if $ivars[ivars].ivars[ivars2].access == 'private'}PrivateVariable{else}Variable{/if}.png" />
							<span class="var-title">
								<span class="var-name">{$ivars[ivars].ivars[ivars2].link}</span>{if $ivars[ivars].ivars[ivars2].ivar_sdesc}: {$ivars[ivars].ivars[ivars2].ivar_sdesc}{/if}<br>
							</span>
						{/section}
					</blockquote> 
				{/section}
			{/if}			
		</div>
	</div>
{/if}
	
{if $methods || $imethods}
	<a name="sec-methods"></a>
	<div class="info-box">
		<div class="info-box-title">Methods</div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			{if $children}
				<a href="#sec-descendents">Descendents</a> |
			{/if}
			{if $vars || $ivars}
				{if $vars}
					<a href="#sec-var-summary">Vars</a> (<a href="#sec-vars">details</a>)
				{else}
					<a href="#sec-vars">Vars</a>
				{/if}
			{/if}
			{if $methods}
				<a href="#sec-method-summary">Methods</a> (<span class="disabled">details</span>)
			{else}
				<span class="disabled">Methods</span>
			{/if}			
		</div>
		<div class="info-box-body">
			{include file="method.tpl"}
			{if $imethods}
				<h4>Inherited Methods</h4>
				<a name='inherited_methods'><!-- --></a>	
				{section name=imethods loop=$imethods}
					<!-- =========== Summary =========== -->
					<p>Inherited From <span class="classname">{$imethods[imethods].parent_class}</span></p>
					<blockquote>
						{section name=im2 loop=$imethods[imethods].imethods}
							<img src="{$subdir}media/images/{if $imethods[imethods].imethods[im2].constructor}Constructor{elseif $imethods[imethods].imethods[im2].destructor}Destructor{elseif $imethods[imethods].imethods[im2].access == 'private'}{if $imethods[imethods].imethods[im2].abstract}Abstract{/if}PrivateMethod{else}{if $imethods[imethods].imethods[im2].abstract}Abstract{/if}Method{/if}.png" alt=" "/>
							<span class="method-name">{$imethods[imethods].imethods[im2].link}</span>{if $imethods[imethods].imethods[im2].ifunction_sdesc}: {$imethods[imethods].imethods[im2].ifunction_sdesc}{/if}<br>
						{/section}
					</blockquote>
				{/section}
			{/if}			
		</div>
	</div>
{/if}
	
{include file="footer.tpl" top3=true}
