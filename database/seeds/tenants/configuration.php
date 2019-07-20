<?php

use Illuminate\Database\Seeder;

class Configuration extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {

		//$objectId = $this->action('GET', 'Controller@createObjectId');

		DB::table('companies')->insert([

			'name' => "Sample company",
			'website' => "salun.pos",
			'country' => "MY",
			'timezone' => 'Asia/Kuala_Lumpur',
			'currency' => 'MYR',
			'time_format' => '24-hours',
			'user_id' => 1,
		]);

		DB::table('stores')->insert([

			'name' => "Salon A",
			'contact_number' => "+630 88888888",
			'street1' => "",
			'street2' => "",
			'zipcode' => "90000",
			'city' => "Kuala Lumpur",
			'state' => "FT",
			'country' => "MY",
			'timezone' => 'Asia/Kuala_Lumpur',
			'currency' => 'MYR',
			'email' => "salunA@salun.app",
			'online_booking' => true,
			'user_id' => 1,
		]);

		DB::table('categories')->insert([
			'name' => "System",
			'service' => true,
			'user_id' => 1,
		]);
		DB::table('categories')->insert([
			'name' => "Haircut",
			'service' => true,
			'user_id' => 1,
		]);

		DB::table('categories')->insert([
			'name' => "Hair care",
			'service' => false,
			'user_id' => 1,
		]);

		DB::table('config_bookings')->insert([

			'max_book_hour' => 0,
			'max_book_month' => 6,
			'max_cancel_hour' => 0,
			'policy' => 'Kindly arrive 10mins earlier.',
			'choose_staff' => true,
			'user_id' => 1,
		]);

		DB::table('config_staff_notifications')->insert([

			'enable_notification' => true,
			'send_to_staff' => true,
			'send_to_specific_email' => false,
			'user_id' => 1,
		]);

		DB::table('config_notifications')->insert([

			'enable_notification' => true,
			'notification_delay' => 60000,
			'enable_reminders' => true,
			'advance_reminder' => 8640000000,
			'reminders_subject' => "Reminder about your appointment",
			'reminders_message' => "Hi CLIENT_FIRST_NAME, /n This is a friendly reminder about your appointment with booking reference BOOKING_REFERENCE. Here are the details: /n BUSINESS_NAME/n SERVICE_NAME/n BOOKING_DATE_TIME/n /n At this location:/n /n LOCATION_NAME/n LOCATION_PHONE/n /n Need to change your appointment? Please contact BUSINESS_NAME on LOCATION_PHONE.",
			'enable_conformation' => false,
			'conformation_subject' => "Your new appointment is confirmed",
			'conformation_message' => "Hi CLIENT_FIRST_NAME, /n  Your new appointment with booking reference BOOKING_REFERENCE is confirmed. Here are the details: /n BUSINESS_NAME/n SERVICE_NAME/n BOOKING_DATE_TIME/n /n At this location:/n /n LOCATION_NAME/n LOCATION_PHONE/n /n Need to change your appointment? Please contact BUSINESS_NAME on LOCATION_PHONE.",
			'enable_reschedules' => true,
			'reschedules_subject' => "Your appointment has changed",
			'reschedules_message' => "Hi CLIENT_FIRST_NAME, /n  Your appointment with booking reference BOOKING_REFERENCE has been updated. Here are the new details: /n BUSINESS_NAME/n SERVICE_NAME/n BOOKING_DATE_TIME/n /n At this location:/n /n LOCATION_NAME/n LOCATION_PHONE/n /n Need to change your appointment? Please contact BUSINESS_NAME on LOCATION_PHONE.",
			'enable_cancelations' => true,
			'cancelations_subject' => "Your appointment was cancelled",
			'cancelations_message' => "Hi CLIENT_FIRST_NAME, /n  Your appointment with booking reference BOOKING_REFERENCE was cancelled. Here are the details: /n BUSINESS_NAME/n SERVICE_NAME/n BOOKING_DATE_TIME/n /n At this location:/n /n LOCATION_NAME/n LOCATION_PHONE/n /n Need to get in touch?Please contact BUSINESS_NAME on LOCATION_PHONE.",
			'enable_thanks' => true,
			'thanks_subject' => "Thank you for visiting",
			'thanks_message' => "Hi CLIENT_FIRST_NAME,/n /n We just wanted to say thanks for visiting us at BUSINESS_NAME for your appointment (reference BOOKING_REFERENCE)./n /n We hope you enjoyed your visit and look forward to seeing you return soon./n /n If you wish to share any feedback please feel free to get in touch on LOCATION_PHONE./n /n Warm regards,/n /n BUSINESS_NAME",
			'user_id' => 1,
		]);

		//Referral Sources

		$referral_id = DB::table('referral_sources')->insertGetId([
			'name' => "Walk-In",
			'active' => true,
			'user_id' => 1,
		]);
		DB::table('system_lockers')->insert([
			'item_id' => $referral_id,
			'item_table' => "referral_sources",
			'user_id' => 1,
		]);
		DB::table('referral_sources')->insert([

			'name' => "Online Booking",
			'active' => true,
			'user_id' => 1,
		]);
		DB::table('referral_sources')->insert([

			'name' => "Facebook Booking",
			'active' => true,
			'user_id' => 1,
		]);
		DB::table('cancellation_reasons')->insert([

			'name' => "Duplicate appointment",
			'user_id' => 1,
		]);
		DB::table('cancellation_reasons')->insert([

			'name' => "Appointment made by mistake",
			'user_id' => 1,
		]);
		DB::table('cancellation_reasons')->insert([

			'name' => "Client not available",
			'user_id' => 1,
		]);

		//Payment types

		$paymentType_id = DB::table('payment_types')->insertGetId([
			'name' => "Cash",
			'code' => "CS",
			'user_id' => 1,
		]);
		DB::table('payment_types')->insert([

			'name' => "Credit",
			'code' => "CR",
			'user_id' => 1,
		]);
		DB::table('payment_types')->insert([

			'name' => "Other",
			'code' => "OT",
			'user_id' => 1,
		]);

		DB::table('system_lockers')->insert([
			'item_id' => $paymentType_id,
			'item_table' => "payment_types",
			'user_id' => 1,
		]);

		DB::table('cards')->insert([

			'name' => "Debit",
			'user_id' => 1,
		]);
		DB::table('cards')->insert([

			'name' => "Credit",
			'user_id' => 1,
		]);

		DB::table('cards')->insert([

			'name' => "Other",
			'user_id' => 1,
		]);
		//Taxes
		DB::table('tax_rates')->insert([

			'name' => "GST SR",
			'code' => "SR",
			'rate' => 6,
			'country' => "MY",
			'user_id' => 1,
		]);
		DB::table('tax_rates')->insert([

			'name' => "GST ZR",
			'code' => "ZR",
			'rate' => 0,
			'country' => "MY",
			'user_id' => 1,
		]);

		//Sales Config
		DB::table('config_sales')->insert([

			'price_include_tax' => false,
			'auto_print_tax_invoice' => true,
			'tax_invoice_msg' => "",
			'calc_staff_commission_before_discount' => false,
			'calc_staff_commission_include_tax' => false,
			'voucher_expire' => 0,
			'user_id' => 1,
		]);
		DB::table('loyalty')->insert([

			'name' => "Default",
			'point' => 1,
			'equal' => 1,
			'user_id' => 1,
		]);
	}
}
