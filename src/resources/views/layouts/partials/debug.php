<style>
	body
	{
		margin: 0;
		padding: 20px 26px;
		font-family: Courier New, Courier, Lucida Sans Typewriter, Lucida Typewriter, monospace;
	}

	#pxl_debug_table
	{
		width: 100%;
		font-size: .9em;
		border-collapse: collapse;
	}

	#pxl_debug_table th, #pxl_debug_table td
	{
		vertical-align: top;
		text-align: left;
		padding: 12px 14px;
	}

	#pxl_debug_table tr:nth-child(odd) th, #pxl_debug_table tr:nth-child(odd) td
	{
		background-color: #F4F4F4;
	}

	#pxl_debug_table th
	{
		width: 300px;
	}

	#pxl_debug_table td.array
	{
		white-space: pre;
	}
</style>

<table id="pxl_debug_table">
	<tr>
		<th>$current_controller</th>
		<td class="array"><?= $current_controller ?></td>
	</tr>
	<tr>
		<th>$current_action</th>
		<td class="array"><?= $current_action ?></td>
	</tr>
	<tr>
		<th>$current_page</th>
		<td><?= $current_page ?></td>
	</tr>
	<tr>
		<th>$js_layout_path</th>
		<td><?= $js_layout_path ?></td>
	</tr>
	<tr>
		<th>$js_auto_path</th>
		<td><?= $js_auto_path ?></td>
	</tr>
	<tr>
		<th>$css_layout_path</th>
		<td><?= $css_layout_path ?></td>
	</tr>
	<tr>
		<th>$css_auto_path</th>
		<td><?= $css_auto_path ?></td>
	</tr>
	<tr>
		<th>CSS Files</th>
		<td class="array"><?= $css_files ?></td>
	</tr>
	<tr>
		<th>JS Files</th>
		<td class="array"><?= $js_files ?></td>
	</tr>
	<tr>
		<th>Inline JS</th>
		<td class="array"><?= $inline_js_variables ?></td>
	</tr>
	<tr>
		<th>Loaded Libraries</th>
		<td class="array"><?= $loaded_libraries ?></td>
	</tr>
</table>