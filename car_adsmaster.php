<?php

// user_id
// ad_title
// year_id
// registered_in
// city_id
// make_id
// model_id
// demand_price
// email
// name
// address
// allow_whatsapp

?>
<?php if ($car_ads->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $car_ads->TableCaption() ?></h4> -->
<table id="tbl_car_adsmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $car_ads->TableCustomInnerHtml ?>
	<tbody>
<?php if ($car_ads->user_id->Visible) { // user_id ?>
		<tr id="r_user_id">
			<td><?php echo $car_ads->user_id->FldCaption() ?></td>
			<td<?php echo $car_ads->user_id->CellAttributes() ?>>
<span id="el_car_ads_user_id">
<span<?php echo $car_ads->user_id->ViewAttributes() ?>>
<?php echo $car_ads->user_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($car_ads->ad_title->Visible) { // ad_title ?>
		<tr id="r_ad_title">
			<td><?php echo $car_ads->ad_title->FldCaption() ?></td>
			<td<?php echo $car_ads->ad_title->CellAttributes() ?>>
<span id="el_car_ads_ad_title">
<span<?php echo $car_ads->ad_title->ViewAttributes() ?>>
<?php echo $car_ads->ad_title->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($car_ads->year_id->Visible) { // year_id ?>
		<tr id="r_year_id">
			<td><?php echo $car_ads->year_id->FldCaption() ?></td>
			<td<?php echo $car_ads->year_id->CellAttributes() ?>>
<span id="el_car_ads_year_id">
<span<?php echo $car_ads->year_id->ViewAttributes() ?>>
<?php echo $car_ads->year_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($car_ads->registered_in->Visible) { // registered_in ?>
		<tr id="r_registered_in">
			<td><?php echo $car_ads->registered_in->FldCaption() ?></td>
			<td<?php echo $car_ads->registered_in->CellAttributes() ?>>
<span id="el_car_ads_registered_in">
<span<?php echo $car_ads->registered_in->ViewAttributes() ?>>
<?php echo $car_ads->registered_in->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($car_ads->city_id->Visible) { // city_id ?>
		<tr id="r_city_id">
			<td><?php echo $car_ads->city_id->FldCaption() ?></td>
			<td<?php echo $car_ads->city_id->CellAttributes() ?>>
<span id="el_car_ads_city_id">
<span<?php echo $car_ads->city_id->ViewAttributes() ?>>
<?php echo $car_ads->city_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($car_ads->make_id->Visible) { // make_id ?>
		<tr id="r_make_id">
			<td><?php echo $car_ads->make_id->FldCaption() ?></td>
			<td<?php echo $car_ads->make_id->CellAttributes() ?>>
<span id="el_car_ads_make_id">
<span<?php echo $car_ads->make_id->ViewAttributes() ?>>
<?php echo $car_ads->make_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($car_ads->model_id->Visible) { // model_id ?>
		<tr id="r_model_id">
			<td><?php echo $car_ads->model_id->FldCaption() ?></td>
			<td<?php echo $car_ads->model_id->CellAttributes() ?>>
<span id="el_car_ads_model_id">
<span<?php echo $car_ads->model_id->ViewAttributes() ?>>
<?php echo $car_ads->model_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($car_ads->demand_price->Visible) { // demand_price ?>
		<tr id="r_demand_price">
			<td><?php echo $car_ads->demand_price->FldCaption() ?></td>
			<td<?php echo $car_ads->demand_price->CellAttributes() ?>>
<span id="el_car_ads_demand_price">
<span<?php echo $car_ads->demand_price->ViewAttributes() ?>>
<?php echo $car_ads->demand_price->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($car_ads->_email->Visible) { // email ?>
		<tr id="r__email">
			<td><?php echo $car_ads->_email->FldCaption() ?></td>
			<td<?php echo $car_ads->_email->CellAttributes() ?>>
<span id="el_car_ads__email">
<span<?php echo $car_ads->_email->ViewAttributes() ?>>
<?php echo $car_ads->_email->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($car_ads->name->Visible) { // name ?>
		<tr id="r_name">
			<td><?php echo $car_ads->name->FldCaption() ?></td>
			<td<?php echo $car_ads->name->CellAttributes() ?>>
<span id="el_car_ads_name">
<span<?php echo $car_ads->name->ViewAttributes() ?>>
<?php echo $car_ads->name->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($car_ads->address->Visible) { // address ?>
		<tr id="r_address">
			<td><?php echo $car_ads->address->FldCaption() ?></td>
			<td<?php echo $car_ads->address->CellAttributes() ?>>
<span id="el_car_ads_address">
<span<?php echo $car_ads->address->ViewAttributes() ?>>
<?php echo $car_ads->address->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($car_ads->allow_whatsapp->Visible) { // allow_whatsapp ?>
		<tr id="r_allow_whatsapp">
			<td><?php echo $car_ads->allow_whatsapp->FldCaption() ?></td>
			<td<?php echo $car_ads->allow_whatsapp->CellAttributes() ?>>
<span id="el_car_ads_allow_whatsapp">
<span<?php echo $car_ads->allow_whatsapp->ViewAttributes() ?>>
<?php echo $car_ads->allow_whatsapp->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
