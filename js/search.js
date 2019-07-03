/*
Template Name: Nokri Job Board Theme
Author: ScriptsBundle
Version: 1.0
Designed and Development by: ScriptsBundle  */
(function ($) {
	"use strict";
	var nokri_ajax_url = $('#nokri_ajax_url').val();
	$('#make_id').on('change', function () {
		$('.cp-loader').show();
		$('#select_modal').hide();
		$('#select_modals').hide();
		$('#select_forth_div').hide();
		var cat_s_id = $('#make_id').val();
		$('input[name=cat_id]').val(cat_s_id);
		$.post(nokri_ajax_url, {
			action: 'sb_get_sub_cat_search',
			cat_id: cat_s_id,
		}).done(function (response) {
			$('.cp-loader').hide();
			//if (response == '') {
				//$("#search_form").submit();
			//} else {
				$('#select_modal').show();
				$('#select_modal').html(response);
				$(".questions-category").select2({
					placeholder: get_strings.option_select,
					allowClear: true,
				});
			//}
		});
	});

	$('#category_search').on("click", function () {
		$(this).closest("form").submit();
	});

	$('.location_search').on("click", function () {
		$(this).closest("form").submit();
	});

	$(document).on('change', '#cats_response', function () {
		$('.cp-loader').show();
		$('#select_modals').hide();
		$('#select_forth_div').hide();
		var cat_s_id = $('#cats_response').val();
		$('input[name=cat_id]').val(cat_s_id);
		$.post(nokri_ajax_url, {
			action: 'sb_get_sub_sub_cat_search',
			cat_id: cat_s_id,
		}).done(function (response) {
			$('.cp-loader').hide();
			//if (response == '') {
				//$("#search_form").submit();
			//} else {

				$('#select_modals').show();
				$('#select_modals').html(response);
				$(".search-select").select2({
					placeholder: get_strings.option_select,
					allowClear: true,
				});
			//}
		});
	});

	$(document).on('change', '#select_version', function () {
		$('.cp-loader').show();
		$('#select_forth_div').hide();
		var cat_s_id = $('#select_version').val();
		$('input[name=cat_id]').val(cat_s_id);
		$.post(nokri_ajax_url, {
			action: 'sb_get_sub_sub_sub_cat_search',
			cat_id: cat_s_id,
		}).done(function (response) {
			$('.cp-loader').hide();
			//if (response == '') {
				//$("#search_form").submit();
			//} else {
				$('#select_forth_div').show();
				$('#select_forth_div').html(response);
				$(".search-select").select2({
					placeholder: get_strings.option_select,
					allowClear: true,
				});
			//}
		});
	});

	$(document).on('change', '#select_forth', function () {
		var cat_s_id = $('#select_forth').val();
		$('input[name=cat_id]').val(cat_s_id);
	});

	/*iCheck*/
	$(document).ready(function () {
		$('.input-icheck-search').iCheck({
			checkboxClass: 'icheckbox_square',
			radioClass: 'iradio_square',
			increaseArea: '20%' // optional
		});
	});


	$('.change_order').on("select2:select", function (e) {
		$(this).closest("form").submit();
	});
	
	
	

	/*On click search form submit*/
	$('.input-icheck-search').on('ifChecked', function (event) {
		$('.cp-loader').show();
		$(this).closest("form").submit();
	});


	$('.iradio_square').on('ifChecked', function (event) {
		$('.cp-loader').show();
		$(this).closest("form").submit();
	});

	$('.change_select').on("select2:select", function (e) {
		$(this).closest("form").submit();
	});

	$('.submit_on_select').on('click', function () {
		$('.cp-loader').show();
		$(this).closest("form").submit();
	});


	$('.show_next').on('click', function () {
		var cur_id = $(this).attr('data-tax-id');
		$('.hide_nexts-' + cur_id).show();
		$(this).hide();
	});


	/* Select Country */


	$(document).on('change', '#countries_id', function () {
		$('.cp-loader').show();
		$('#select_modals_state').hide();
		$('#select_forth_div_city').hide();
		$('#select_modal_countries').hide();
		var countries_s_id = $('#countries_id').val();
		$('input[name=location_id]').val(countries_s_id);
		$.post(nokri_ajax_url, {
			action: 'get_countries_search',
			country_id: countries_s_id,
		}).done(function (response) {
			$('.cp-loader').hide();
			//if (response == '') {
				//$("#search_form").submit();
			//} else {
				 $("#location_id").val(countries_s_id);
				$('#select_modal_countries').show();
				$('#select_modal_countries').html(response);
				$(".questions-category").select2({
					placeholder: get_strings.option_select,
					allowClear: true,
				});
			//}
		});
	});


	/* Select State */

	$(document).on('change', '#state_response', function () {
		$('.cp-loader').show();
		$('#select_forth_div_city').hide();
		var countries_s_id = $('#state_response').val();
		$('input[name=location_id]').val(countries_s_id);
		$.post(nokri_ajax_url, {
			action: 'get_cities_search',
			country_id: countries_s_id,
		}).done(function (response) {
			$('.cp-loader').hide();
			//if (response == '') {
				//$("#search_form").submit();
			//} else {
				$("#location_id").val(countries_s_id);
				$('#select_forth_div_city').show();
				$('#select_forth_div_city').html(response);
				$(".questions-category").select2({
					placeholder: get_strings.option_select,
					allowClear: true,
				});
			//}
		});
	});


	/* Select City */
	$(document).on('change', '#countries_response', function () {
		$('.cp-loader').show();
		$('#select_forth_div_city').hide();
		var countries_s_id = $('#countries_response').val();
		$('input[name=location_id]').val(countries_s_id);
		$.post(nokri_ajax_url, {
			action: 'get_states_search',
			country_id: countries_s_id,
		}).done(function (response) {
			$('.cp-loader').hide();
			//if (response == '') {
				//$("#search_form").submit();
			//} else {
				$("#location_id").val(countries_s_id);
				$('#select_modals_state').show();
				$('#select_modals_state').html(response);
				$(".questions-category").select2({
					placeholder: get_strings.option_select,
					allowClear: true,
				});
			//}
		});
	});


	$(document).on('change', '#cities_response', function () {
		var countries_s_id = $('#cities_response').val();
		$('input[name=location_id]').val(countries_s_id);
		$("#location_id").val(countries_s_id);
		//$("#search_form").submit();
	});

	$('.show_records').on('click', function () {
		var show_now = $(this).attr('data-attr-id');
		var hide_now = $(this).attr('data-attr-hide');
		$('.hide_now_' + hide_now).hide();
		$('.' + show_now).show();
	});

})(jQuery);