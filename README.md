# SheerID-OpenCart

SheerID Verification Plugin for OpenCart

## About This Module

This extension adds the ability to protect targeted discounts and offers via the SheerID instant verification API.  For more information, [visit our web site](http://www.sheerid.com).

## Installation

1. Back up your OpenCart installation (safety first!).
2. Upload the contents of `upload/` to the appropriate locations within your OpenCart installation root.
> Note: this distribution contains a git submodule located under `upload/system/sheerid/library`.  If you are installing this plugin from a GitHub zip file export or have not initialized the submodule, you will need to make sure to download the [SheerID PHP Library](https://github.com/sheerid/sheerid-php) and unzip it into this directory. Users who have downloaded the extension directly from the OpenCart site can ignore this warning.
3. Navigate to your OpenCart admin backend.
4. From the "Extensions" menu, select "Order Totals".
5. Look for the row labeled "SheerID" and click "Install".
6. From the "Extensions" menu, select "Modules".
7. Look for the row labeled "SheerID Verification" and click "Install".

## Configuration

### Obtain a SheerID Access Token

This plugin requires access to the SheerID API in order to function.  After installing the module files, you must create a SheerID account and generate an API access token by following the steps below.

1. In a web browser, navigate to the [SheerID Signup Page](https://services-sandbox.sheerid.com/home/signup.html).
2. Fill out the required fields to create a new account.  Make sure to adhere to the username and password restrictions.
3. After successfully creating your account, follow the link and log in to your SheerID account.
4. Click on the "API Access Tokens" link listed under the "Settings" header.
5. Click the green button to issue a new access token.
6. Copy the alphanumeric token which is displayed.
7. Navigate to your OpenCart admin backend. From the "Extensions" menu, select "Order Totals".  Click the "Edit" link in the SheerID row.
8. Paste the copied access token into the "Access Token" field and click the Save button.
9. Your access token should now be saved in the SheerID OpenCart extension settings.  Note that it is now masked with asterisks in order to protect the secrecy of this token.

### Protecting a Coupon Code

1. In the OpenCart admin backend, from the "Sales" menu select "Coupons".  Click the "Insert" button and configure a coupon code as normal.
2. From the "Extensions" menu select "Order Totals" and click on the "Edit" link for the SheerID row.
3. In the "Coupons" section, find the appropriate row for the coupon code you wish to protect, and check the checkbox marked "Require verification". Leaving this checkbox unchecked results in the coupon code being treated as if this plugin were not installed - no verification will be required.
4. In the bank of checkboxes that appears, select one or more affiliation types that you will allow to redeem this coupon.
5. Save the SheerID settings - usage of the coupon code is now restricted to the affiliations you chose in a previous step.

### Promoting Your Offer

Now that the coupon code is protected, users won't be able to add it to a cart directly.  You must create an information page that guides your customers through the verification process.

1. From the "Catalog" menu select "Information".  Insert a new Information page that describes your offer and provides any necessary instructions or conditions to the user.  Save the information page.
2. From the "Extensions" menu select "Modules", then click "Edit" in the SheerID Verification row.
3. Click "Add Module" to add a new module row.  Select the coupon you wish to protect, and the information page that was just created in step 1. Save these settings.
4. Navigate to the information page you created by adding it to the footer menu or noting its ID and constructing the URL appropriately.  This is the landing page URL where visitors can come to be verified - drive traffic to this page by adding banners within your site or publicizing the URL to your target market.  When visitors fill out the form and get successfully verified, they will automatically have the coupon applied to their cart.
