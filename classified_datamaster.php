<?php

// title
// car_type
// car_make_company_id
// car_model_id
// price_range
// engine_capicity
// status

?>
<?php if ($classified_data->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $classified_data->TableCaption() ?></h4> -->
<table id="tbl_classified_datamaster" class="table table-bordered table-striped ewViewTable">
<?php echo $classified_data->TableCustomInnerHtml ?>
	<tbody>
<?php if ($classified_data->title->Visible) { // title ?>
		<tr id="r_title">
			<td><?php echo $classified_data->title->FldCaption() ?></td>
			<td<?php echo $classified_data->title->CellAttributes() ?>>
<span id="el_classified_data_title">
<span<?php echo $classified_data->title->ViewAttributes() ?>>
<?php echo $classified_data->title->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($classified_data->car_type->Visible) { // car_type ?>
		<tr id="r_car_type">
			<td><?php echo $classified_data->car_type->FldCaption() ?></td>
			<td<?php echo $classified_data->car_type->CellAttributes() ?>>
<span id="el_classified_data_car_type">
<span<?php echo $classified_data->car_type->ViewAttributes() ?>>
<?php echo $classified_data->car_type->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($classified_data->car_make_company_id->Visible) { // car_make_company_id ?>
		<tr id="r_car_make_company_id">
			<td><?php echo $classified_data->car_make_company_id->FldCaption() ?></td>
			<td<?php echo $classified_data->car_make_company_id->CellAttributes() ?>>
<span id="el_classified_data_car_make_company_id">
<span<?php echo $classified_data->car_make_company_id->ViewAttributes() ?>>
<?php echo $classified_data->car_make_company_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($classified_data->car_model_id->Visible) { // car_model_id ?>
		<tr id="r_car_model_id">
			<td><?php echo $classified_data->car_model_id->FldCaption() ?></td>
			<td<?php echo $classified_data->car_model_id->CellAttributes() ?>>
<span id="el_classified_data_car_model_id">
<span<?php echo $classified_data->car_model_id->ViewAttributes() ?>>
<?php echo $classified_data->car_model_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($classified_data->price_range->Visible) { // price_range ?>
		<tr id="r_price_range">
			<td><?php echo $classified_data->price_range->FldCaption() ?></td>
			<td<?php echo $classified_data->price_range->CellAttributes() ?>>
<span id="el_classified_data_price_range">
<span<?php echo $classified_data->price_range->ViewAttributes() ?>>
<?php echo $classified_data->price_range->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($classified_data->engine_capicity->Visible) { // engine_capicity ?>
		<tr id="r_engine_capicity">
			<td><?php echo $classified_data->engine_capicity->FldCaption() ?></td>
			<td<?php echo $classified_data->engine_capicity->CellAttributes() ?>>
<span id="el_classified_data_engine_capicity">
<span<?php echo $classified_data->engine_capicity->ViewAttributes() ?>>
<?php echo $classified_data->engine_capicity->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($classified_data->status->Visible) { // status ?>
		<tr id="r_status">
			<td><?php echo $classified_data->status->FldCaption() ?></td>
			<td<?php echo $classified_data->status->CellAttributes() ?>>
<span id="el_classified_data_status">
<span<?php echo $classified_data->status->ViewAttributes() ?>>
<?php echo $classified_data->status->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
