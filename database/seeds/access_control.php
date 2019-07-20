<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class access_control extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new Role();
		$admin->name         = 'admin';
		$admin->display_name = 'Administrator'; 
		$admin->description  = 'The system administrator'; 
		$admin->save();    

		$manager = new Role();
		$manager->name         = 'manager';
		$manager->display_name = 'Manager'; 
		$manager->description  = 'The store manager'; 
		$manager->save();    

		$cashier = new Role();
		$cashier->name  = 'cashier';
		$cashier->display_name = 'Cashier'; 
		$cashier->description  = 'The cashier'; 
		$cashier->save();    

        //APPOINTMENTS

		$ViewAppointment = new Permission();
		$ViewAppointment->name         = 'view-appointments';
		$ViewAppointment->display_name = 'View Appointments'; 
		$ViewAppointment->description  = 'View appointments'; 
		$ViewAppointment->save();
        $admin->attachPermission($ViewAppointment);
        $manager->attachPermission($ViewAppointment);
        $cashier->attachPermission($ViewAppointment);

		$CreateAppointment = new Permission();
		$CreateAppointment->name         = 'create-appointments';
		$CreateAppointment->display_name = 'Create Appointments'; 
		$CreateAppointment->description  = 'Create new appointments'; 
		$CreateAppointment->save();
        $admin->attachPermission($CreateAppointment);
        $manager->attachPermission($CreateAppointment);
        $cashier->attachPermission($CreateAppointment);

        $EditAppointment = new Permission();
		$EditAppointment->name         = 'edit-appointments';
		$EditAppointment->display_name = 'Edit Appointments'; 
		$EditAppointment->description  = 'Edit an appointment'; 
		$EditAppointment->save();
        $admin->attachPermission($EditAppointment);
        $manager->attachPermission($EditAppointment);

        $RemoveAppointment = new Permission();
		$RemoveAppointment->name         = 'remove-appointments';
		$RemoveAppointment->display_name = 'Remove Appointments'; 
		$RemoveAppointment->description  = 'Remove an appointment'; 
		$RemoveAppointment->save();
        $admin->attachPermission($RemoveAppointment);
        $manager->attachPermission($RemoveAppointment);


        //INVOICES

		$ViewInvoice = new Permission();
		$ViewInvoice->name         = 'view-invoices';
		$ViewInvoice->display_name = 'View Invoices'; 
		$ViewInvoice->description  = 'View invoices'; 
		$ViewInvoice->save();
        $admin->attachPermission($ViewInvoice);
        $manager->attachPermission($ViewInvoice);
        $cashier->attachPermission($ViewInvoice);

		$CreateInvoice = new Permission();
		$CreateInvoice->name         = 'create-invoices';
		$CreateInvoice->display_name = 'Create Invoices'; 
		$CreateInvoice->description  = 'Create new invoices'; 
		$CreateInvoice->save();
        $admin->attachPermission($CreateInvoice);
        $manager->attachPermission($CreateInvoice);
        $cashier->attachPermission($CreateInvoice);

        $EditInvoice = new Permission();
		$EditInvoice->name         = 'edit-invoices';
		$EditInvoice->display_name = 'Edit Invoices'; 
		$EditInvoice->description  = 'Edit an invoice'; 
		$EditInvoice->save();
        $admin->attachPermission($EditInvoice);
        $manager->attachPermission($EditInvoice);

        $RemoveInvoice = new Permission();
		$RemoveInvoice->name         = 'remove-invoices';
		$RemoveInvoice->display_name = 'Remove Invoices'; 
		$RemoveInvoice->description  = 'Remove an invoice'; 
		$RemoveInvoice->save();
        $admin->attachPermission($RemoveInvoice);
        $manager->attachPermission($RemoveInvoice);



        //PAYMENTS

		$ViewPayment = new Permission();
		$ViewPayment->name         = 'view-payments';
		$ViewPayment->display_name = 'View Payments'; 
		$ViewPayment->description  = 'View payments'; 
		$ViewPayment->save();
        $admin->attachPermission($ViewPayment);
        $manager->attachPermission($ViewPayment);
        $cashier->attachPermission($ViewPayment);

		$CreatePayment = new Permission();
		$CreatePayment->name         = 'create-payments';
		$CreatePayment->display_name = 'Create Payments'; 
		$CreatePayment->description  = 'Create new payments'; 
		$CreatePayment->save();
        $admin->attachPermission($CreatePayment);
        $manager->attachPermission($CreatePayment);
        $cashier->attachPermission($CreatePayment);

        $EditPayment = new Permission();
		$EditPayment->name         = 'edit-payments';
		$EditPayment->display_name = 'Edit Payments'; 
		$EditPayment->description  = 'Edit a payment'; 
		$EditPayment->save();
        $admin->attachPermission($EditPayment);
        $manager->attachPermission($EditPayment);

        $RemovePayment = new Permission();
		$RemovePayment->name         = 'remove-payments';
		$RemovePayment->display_name = 'Remove Payments'; 
		$RemovePayment->description  = 'Remove a payment'; 
		$RemovePayment->save();
        $admin->attachPermission($RemovePayment);
        $manager->attachPermission($RemovePayment);

        //RECEIVES

		$ViewReceive = new Permission();
		$ViewReceive->name         = 'view-receives';
		$ViewReceive->display_name = 'View Receives'; 
		$ViewReceive->description  = 'View receives'; 
		$ViewReceive->save();
        $admin->attachPermission($ViewReceive);
        $manager->attachPermission($ViewReceive);
        $cashier->attachPermission($ViewReceive);

		$CreateReceive = new Permission();
		$CreateReceive->name         = 'create-receives';
		$CreateReceive->display_name = 'Create Receives'; 
		$CreateReceive->description  = 'Create new receives'; 
		$CreateReceive->save();
        $admin->attachPermission($CreateReceive);
        $manager->attachPermission($CreateReceive);
        $cashier->attachPermission($CreateReceive);

        $EditReceive = new Permission();
		$EditReceive->name         = 'edit-receives';
		$EditReceive->display_name = 'Edit Receives'; 
		$EditReceive->description  = 'Edit a receive'; 
		$EditReceive->save();
        $admin->attachPermission($EditReceive);
        $manager->attachPermission($EditReceive);

        $RemoveReceive = new Permission();
		$RemoveReceive->name         = 'remove-receives';
		$RemoveReceive->display_name = 'Remove Receives'; 
		$RemoveReceive->description  = 'Remove a receive'; 
		$RemoveReceive->save();
        $admin->attachPermission($RemoveReceive);
        $manager->attachPermission($RemoveReceive);


        //CUSTOMERS

		$ViewCustomer = new Permission();
		$ViewCustomer->name         = 'view-customers';
		$ViewCustomer->display_name = 'View Customers'; 
		$ViewCustomer->description  = 'View customers'; 
		$ViewCustomer->save();
        $admin->attachPermission($ViewCustomer);
        $manager->attachPermission($ViewCustomer);
        $cashier->attachPermission($ViewCustomer);

		$CreateCustomer = new Permission();
		$CreateCustomer->name         = 'create-customers';
		$CreateCustomer->display_name = 'Create Customers'; 
		$CreateCustomer->description  = 'Create new customers'; 
		$CreateCustomer->save();
        $admin->attachPermission($CreateCustomer);
        $manager->attachPermission($CreateCustomer);
        $cashier->attachPermission($CreateCustomer);

        $EditCustomer = new Permission();
		$EditCustomer->name         = 'edit-customers';
		$EditCustomer->display_name = 'Edit Customers'; 
		$EditCustomer->description  = 'Edit a customer'; 
		$EditCustomer->save();
        $admin->attachPermission($EditCustomer);
        $manager->attachPermission($EditCustomer);

        $RemoveCustomer = new Permission();
		$RemoveCustomer->name         = 'remove-customers';
		$RemoveCustomer->display_name = 'Remove Customers'; 
		$RemoveCustomer->description  = 'Remove a customer'; 
		$RemoveCustomer->save();
        $admin->attachPermission($RemoveCustomer);
        $manager->attachPermission($RemoveCustomer);

         //SUPPLIERS

		$ViewSupplier = new Permission();
		$ViewSupplier->name         = 'view-suppliers';
		$ViewSupplier->display_name = 'View Suppliers'; 
		$ViewSupplier->description  = 'View suppliers'; 
		$ViewSupplier->save();
        $admin->attachPermission($ViewSupplier);
        $manager->attachPermission($ViewSupplier);
        $cashier->attachPermission($ViewSupplier);

		$CreateSupplier = new Permission();
		$CreateSupplier->name         = 'create-suppliers';
		$CreateSupplier->display_name = 'Create Suppliers'; 
		$CreateSupplier->description  = 'Create new suppliers'; 
		$CreateSupplier->save();
        $admin->attachPermission($CreateSupplier);
        $manager->attachPermission($CreateSupplier);
        $cashier->attachPermission($CreateSupplier);

        $EditSupplier = new Permission();
		$EditSupplier->name         = 'edit-suppliers';
		$EditSupplier->display_name = 'Edit Suppliers'; 
		$EditSupplier->description  = 'Edit a supplier'; 
		$EditSupplier->save();
        $admin->attachPermission($EditSupplier);
        $manager->attachPermission($EditSupplier);

        $RemoveSupplier = new Permission();
		$RemoveSupplier->name         = 'remove-suppliers';
		$RemoveSupplier->display_name = 'Remove Suppliers'; 
		$RemoveSupplier->description  = 'Remove a supplier'; 
		$RemoveSupplier->save();
        $admin->attachPermission($RemoveSupplier);
        $manager->attachPermission($RemoveSupplier);

          //SERVICES

		$ViewService = new Permission();
		$ViewService->name         = 'view-services';
		$ViewService->display_name = 'View Services'; 
		$ViewService->description  = 'View services'; 
		$ViewService->save();
        $admin->attachPermission($ViewService);
        $manager->attachPermission($ViewService);
        $cashier->attachPermission($ViewService);

		$CreateService = new Permission();
		$CreateService->name         = 'create-services';
		$CreateService->display_name = 'Create Services'; 
		$CreateService->description  = 'Create new services'; 
		$CreateService->save();
        $admin->attachPermission($CreateService);
        $manager->attachPermission($CreateService);
        $cashier->attachPermission($CreateService);

        $EditService = new Permission();
		$EditService->name         = 'edit-services';
		$EditService->display_name = 'Edit Services'; 
		$EditService->description  = 'Edit a service'; 
		$EditService->save();
        $admin->attachPermission($EditService);
        $manager->attachPermission($EditService);

        $RemoveService = new Permission();
		$RemoveService->name         = 'remove-services';
		$RemoveService->display_name = 'Remove Services'; 
		$RemoveService->description  = 'Remove a service'; 
		$RemoveService->save();
        $admin->attachPermission($RemoveService);
        $manager->attachPermission($RemoveService);

        //PRODUCTS

		$ViewProduct = new Permission();
		$ViewProduct->name         = 'view-products';
		$ViewProduct->display_name = 'View Products'; 
		$ViewProduct->description  = 'View products'; 
		$ViewProduct->save();
        $admin->attachPermission($ViewProduct);
        $manager->attachPermission($ViewProduct);
        $cashier->attachPermission($ViewProduct);

		$CreateProduct = new Permission();
		$CreateProduct->name         = 'create-products';
		$CreateProduct->display_name = 'Create Products'; 
		$CreateProduct->description  = 'Create new products'; 
		$CreateProduct->save();
        $admin->attachPermission($CreateProduct);
        $manager->attachPermission($CreateProduct);
        $cashier->attachPermission($CreateProduct);

        $EditProduct = new Permission();
		$EditProduct->name         = 'edit-products';
		$EditProduct->display_name = 'Edit Products'; 
		$EditProduct->description  = 'Edit a product'; 
		$EditProduct->save();
        $admin->attachPermission($EditProduct);
        $manager->attachPermission($EditProduct);

        $RemoveProduct = new Permission();
		$RemoveProduct->name         = 'remove-products';
		$RemoveProduct->display_name = 'Remove Products'; 
		$RemoveProduct->description  = 'Remove a product'; 
		$RemoveProduct->save();
        $admin->attachPermission($RemoveProduct);
        $manager->attachPermission($RemoveProduct);

         //USERS

		$ViewUser = new Permission();
		$ViewUser->name         = 'view-users';
		$ViewUser->display_name = 'View Users'; 
		$ViewUser->description  = 'View users'; 
		$ViewUser->save();
        $admin->attachPermission($ViewUser);
        $manager->attachPermission($ViewUser);
  
		$CreateUser = new Permission();
		$CreateUser->name         = 'create-users';
		$CreateUser->display_name = 'Create Users'; 
		$CreateUser->description  = 'Create new users'; 
		$CreateUser->save();
        $admin->attachPermission($CreateUser);

        $EditUser = new Permission();
		$EditUser->name         = 'edit-users';
		$EditUser->display_name = 'Edit Users'; 
		$EditUser->description  = 'Edit a user'; 
		$EditUser->save();
        $admin->attachPermission($EditUser);
      
        $RemoveUser = new Permission();
		$RemoveUser->name         = 'remove-users';
		$RemoveUser->display_name = 'Remove Users'; 
		$RemoveUser->description  = 'Remove a user'; 
		$RemoveUser->save();
        $admin->attachPermission($RemoveUser);
     
        //VIEW ALL REPORTS
        $ViewReport = new Permission();
		$ViewReport->name         = 'view-reports';
		$ViewReport->display_name = 'View All Reports'; 
		$ViewReport->description  = 'View all reports'; 
		$ViewReport->save();
        $admin->attachPermission($ViewReport);
        $manager->attachPermission($ViewReport);

        //SETUP
        $SetupAccount = new Permission();
		$SetupAccount->name         = 'setup-account';
		$SetupAccount->display_name = 'Setup Account'; 
		$SetupAccount->description  = 'Setup account default preference and setting'; 
		$SetupAccount->save();
        $admin->attachPermission($SetupAccount);

        $SetupStaff = new Permission();
		$SetupStaff->name         = 'setup-staff';
		$SetupStaff->display_name = 'Setup Staff'; 
		$SetupStaff->description  = 'Setup staff default preference and setting'; 
		$SetupStaff->save();
        $admin->attachPermission($SetupStaff);

        $SetupSales = new Permission();
		$SetupSales->name         = 'setup-sales';
		$SetupSales->display_name = 'Setup Sales'; 
		$SetupSales->description  = 'Setup sales default preference and setting'; 
		$SetupSales->save();
        $admin->attachPermission($SetupSales);

        $SetupCustomer = new Permission();
		$SetupCustomer->name         = 'setup-customer';
		$SetupCustomer->display_name = 'Setup Customer'; 
		$SetupCustomer->description  = 'Setup customer default preference and setting'; 
		$SetupCustomer->save();
        $admin->attachPermission($SetupCustomer);


        $SetupProduct = new Permission();
		$SetupProduct->name         = 'setup-product';
		$SetupProduct->display_name = 'Setup Product'; 
		$SetupProduct->description  = 'Setup product default preference and setting'; 
		$SetupProduct->save();
        $admin->attachPermission($SetupProduct);



        

        //assign to user
        $admin = User::find(2);
        $admin->attachRole(1);
        $cashier = User::find(3);
        $cashier->attachRole(2);

	}
}
