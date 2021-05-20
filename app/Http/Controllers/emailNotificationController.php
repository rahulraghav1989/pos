<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cookie;
use Tracker;
use Session;
use Validator;

use App\customer;

class emailNotificationController extends Controller
{
    public function sendcustomerinvoice(Request $request)
    {
        $validator = validator::make($request->all(),[
        'customeremail'=>'required'
        ],[
            'customeremail.required'=>'Customer email address is required.'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else
        {
            $updateemail = customer::where('customerID', $request->input('customerid'))->first();
            $updateemail->customeremail = $request->input('customeremail');
            $updateemail->save();

            if($updateemail->save())
            {
                $url = 'https://api.elasticemail.com/v2/email/send';
                $data = array('from' => 'ankit23482@yahoo.com',
                    'fromName' => 'Vodafone Store',
                    'apikey' => '7C8CD416EB4A5D6558682A5EB54D9E7E3A2EA1ADB2D950CC49461A1DED2A2ED86342326B0AEFB27771B99E81A6DC4654',
                    'subject' => 'Purchase Invoice',
                    'to' => $request->input('customeremail'),
                    'bodyHtml' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <style type="text/css">body, html {
      margin: 0px;
      padding: 0px;
      -webkit-font-smoothing: antialiased;
      text-size-adjust: none;
      width: 100% !important;
    }
      table td, table {
      }
      #outlook a {
        padding: 0px;
      }
      .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
        line-height: 100%;
      }
      .ExternalClass {
        width: 100%;
      }
      @media only screen and (max-width: 480px) {
        table tr td table.edsocialfollowcontainer {
          width: auto !important;
        }
        table, table tr td, table td {
          width: 100% !important;
        }
        img {
          width: inherit;
        }
        .layer_2 {
          max-width: 100% !important;
        }
        .edsocialfollowcontainer table {
          max-width: 25% !important;
        }
        .edsocialfollowcontainer table td {
          padding: 10px !important;
        }
      }
    </style>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">
  </head>
  <body style="padding:0; margin: 0;background: #efefef">
    <table style="height: 100%; width: 100%; background-color: #efefef;" align="center">
      <tbody>
        <tr>
          <td valign="top" id="dbody" data-version="2.31" style="width: 100%; height: 100%; padding-top: 30px; padding-bottom: 30px; background-color: #efefef;">
            <!--[if (gte mso 9)|(IE)]><table align="center" style="max-width:600px" width="600" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
            <table class="layer_1" align="center" border="0" cellpadding="0" cellspacing="0" style="max-width: 600px; box-sizing: border-box; width: 100%; margin: 0px auto;">
              <tbody>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #efefef; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" cellpadding="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="edimg" style="padding: 20px; box-sizing: border-box; text-align: center;">
                              <img src="https://api.elasticemail.com/userfile/12301ee9-d04b-4adb-84db-0e43666eaa22/atlantis_logo.png" alt="Image" width="141" style="border-width: 0px; border-style: none; max-width: 141px; width: 100%;">
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #374248; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" cellpadding="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="emptycell" style="padding: 10px;">
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #a6c93b; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 380px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="edtext" style="padding: 20px; text-align: left; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                              <p style="margin: 0px; padding: 0px;">
                                <span style="font-size: 24px;">INVOICE</span>
                              </p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 220px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="edbutton" style="padding: 12px;">
                              <table cellspacing="0" cellpadding="0" style="text-align: center;margin:0 auto;" align="center">
                                <tbody>
                                  <tr>
                                    <td align="center" style="border-radius: 4px; padding: 12px; background: #ffffff;">
                                      <a target="_blank" style="color: #374248; font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; text-decoration: none; display: inline-block;"><span style="color: #374248;">Invoice Number<br>Date<br></span></a></td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #374248; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" cellpadding="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="emptycell" style="padding: 10px;">
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #efefef; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" cellpadding="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="emptycell" style="padding: 20px;">
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 300px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="edtext" style="padding: 20px; text-align: left; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                              <p class="style2" style="margin: 0px; padding: 0px; color: #374248; font-size: 28px; font-family: Helvetica, Arial, sans-serif;">
                                <span style="font-size: 12px;">
                                  <strong>
                                    <span style="font-size: 14px;">Customer:</span>
                                  </strong>
                                  <br>Dear Customer
                                  <br>00
                                  <br>Order ID: 3-2370417230498</span>
                              </p>
                              <p style="margin: 0px; padding: 0px;">
                                <span style="font-size: 12px;">
                                  <br></span>
                              </p>
                              <p style="margin: 0px; padding: 0px;">
                                <span style="font-size: 12px;">
                                  <br></span>
                              </p>
                              <p style="margin: 0px; padding: 0px;">
                                <span style="font-size: 12px;">
                                  <br></span>
                              </p>
                              <p style="margin: 0px; padding: 0px;">
                                <span style="font-size: 12px;">
                                  <strong>Served By
                                  </strong>
                                  <strong>:
                                  </strong> Rahul</span>
                              </p>
                              <p style="margin: 0px; padding: 0px;">
                                <span style="font-size: 12px;">
                                  <br></span>
                              </p>
                              <p style="margin: 0px; padding: 0px;">
                                <span style="font-size: 12px;">
                                  <strong>Sale Type:
                                  </strong> instore</span>
                              </p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 300px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="edtext" style="padding: 20px; text-align: left; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                              <p class="text-right" style="text-align: right; margin: 0px; padding: 0px;">
                                <strong>Store
                                </strong>:
                                <br>Vodafone Kilburn
                                <br>Shop T06 Churchill Shopping center, 400 churchill road
                                <br>881625145
                                <br>
                                <br>
                                <br>Jainish Pty Ltd
                                <br>is a service provider for
                                <br>Vodafone Mobile Store
                                <br>ABN: 86 605 918 670
                              </p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #efefef; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" cellpadding="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="emptycell" style="padding: 20px;">
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <!--[if (gte mso 9)|(IE)]></td><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <thead>
                          <th>Barcode</th>
                          <th>Product</th>
                          <th>Item Price</th>
                          <th>Discount</th>
                          <th>Quantity</th>
                          <th>Totals</th>
                        </thead>
                        <tbody>
                          <tr>
                            <td valign="top" class="edtext" style="padding: 20px; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                              
                            </td>
                            <td valign="top" class="edtext" style="padding: 20px; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                              
                            </td>
                            <td valign="top" class="edtext" style="padding: 20px; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                              
                            </td>
                            <td valign="top" class="edtext" style="padding: 20px; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                              
                            </td>
                            <td valign="top" class="edtext" style="padding: 20px; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                              
                            </td>
                            <td valign="top" class="edtext" style="padding: 20px; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                              
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" cellpadding="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="emptycell" style="padding: 20px;">
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <!--[if (gte mso 9)|(IE)]></td><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="edtext" style="padding: 20px; text-align: left; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                              <p class="style4" style="margin: 0px; padding: 0px; color: #374248; font-size: 16px; font-family: Helvetica, Arial, sans-serif;">
                                <strong>
                                </strong>
                              </p>Thank you for shopping at
                              <br>Jainish Pty Ltd
                              <br>- Vodafone Kilburn
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <!--[if (gte mso 9)|(IE)]></td><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="edtext" style="padding: 20px; text-align: left; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                              <p style="margin: 0px; padding: 0px;">1. Please choose carefully. We don't refund or exchange for change of mind or wrong selections (unless a statutory cooling-off period applies)
                                <br>2.Please keep your receipt as proof of purchase. If we need to help you with an issue, please keep your receipt as proof of purchase. File it away somewhere safe in case you need it.
                                <br>3. For Full Details visit: http://www.vodafone.com.au/aboutvodafone/legal/repairpolicy
                              </p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="edtext" style="padding: 20px; text-align: left; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #efefef; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" cellpadding="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="emptycell" style="padding: 20px;">
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" cellpadding="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="edimg" style="padding: 20px; box-sizing: border-box; text-align: center;">
                              <img src="https://api.elasticemail.com/userfile/12301ee9-d04b-4adb-84db-0e43666eaa22/atlantis_logo.png" alt="Image" width="141" style="border-width: 0px; border-style: none; max-width: 141px; width: 100%;">
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="edtext" style="padding: 20px; text-align: left; color: #5f5f5f; font-size: 14px; font-family: Helvetica, Arial, sans-serif; word-break: break-word; direction: ltr; box-sizing: border-box;">
                              <p class="text-center" style="text-align: center; margin: 0px; padding: 0px;">
                                <a href="#" style="color: #828282; font-size: 14px; font-family: Helvetica, Arial, sans-serif; text-decoration: none;">atlantis@example.com</a>&nbsp;| 000 - 000 - 000 | 
                                <a href="#" style="color: #828282; font-size: 14px; font-family: Helvetica, Arial, sans-serif; text-decoration: none;">websiteaddress.com</a></p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
                <tr>
                  <td class="drow" valign="top" align="center" style="background-color: #ffffff; box-sizing: border-box; font-size: 0px; text-align: center;">
                    <!--[if (gte mso 9)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"><![endif]-->
                    <div class="layer_2" style="max-width: 600px; display: inline-block; vertical-align: top; width: 100%;">
                      <table border="0" cellspacing="0" class="edcontent" style="border-collapse: collapse;width:100%">
                        <tbody>
                          <tr>
                            <td valign="top" class="edsocialfollow" style="padding: 20px;">
                              <table align="center" style="margin:0 auto" class="edsocialfollowcontainer" cellpadding="0" border="0" cellspacing="0">
                                <tbody>
                                  <tr>
                                    <td>
                                      <!--[if mso]><table align="center" border="0" cellspacing="0" cellpadding="0"><tr><td align="center" valign="top"><![endif]-->
                                      <table align="left" border="0" cellpadding="0" cellspacing="0" data-service="facebook">
                                        <tbody>
                                          <tr>
                                            <td align="center" valign="middle" style="padding: 5px;">
                                              <a href="https://www.facebook.com" target="_blank" style="color: #828282; font-size: 14px; font-family: Helvetica, Arial, sans-serif; text-decoration: none;"><img src="https://api.elasticemail.com/userfile/a18de9fc-4724-42f2-b203-4992ceddc1de/ro_sol_co_32_facebook.png" style="display:block;width:32px;max-width:32px;border:none" alt="Facebook"></a></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <!--[if mso]></td><td align="center" valign="top"><![endif]-->
                                      <!--[if mso]></td><td align="center" valign="top"><![endif]-->
                                      <!--[if mso]></td><td align="center" valign="top"><![endif]-->
                                      <table align="left" border="0" cellpadding="0" cellspacing="0" data-service="twitter">
                                        <tbody>
                                          <tr>
                                            <td align="center" valign="middle" style="padding: 5px;">
                                              <a href="https://www.twitter.com/" target="_blank" style="color: #828282; font-size: 14px; font-family: Helvetica, Arial, sans-serif; text-decoration: none;"><img src="https://api.elasticemail.com/userfile/a18de9fc-4724-42f2-b203-4992ceddc1de/ro_sol_co_32_twitter.png" style="display:block;width:32px;max-width:32px;border:none" alt="Twitter"></a></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <!--[if mso]></td><td align="center" valign="top"><![endif]-->
                                      <!--[if mso]></td><td align="center" valign="top"><![endif]-->
                                      <!--[if mso]></td><td align="center" valign="top"><![endif]-->
                                      <table align="left" border="0" cellpadding="0" cellspacing="0" data-service="googleplus">
                                        <tbody>
                                          <tr>
                                            <td align="center" valign="middle" style="padding: 5px;">
                                              <a href="https://plus.google.com" target="_blank" style="color: #828282; font-size: 14px; font-family: Helvetica, Arial, sans-serif; text-decoration: none;"><img src="https://api.elasticemail.com/userfile/a18de9fc-4724-42f2-b203-4992ceddc1de/ro_sol_co_32_googleplus.png" style="display:block;width:32px;max-width:32px;border:none" alt="Google+"></a></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <!--[if mso]></td></tr></table><![endif]-->
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                  </td>
                </tr>
              </tbody>
            </table>
            <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>',
                    'isTransactional' => false);
                
                // use key 'http' even if you send the request to https://...
                $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data)
                    )
                );
                
                $context  = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                if ($result === FALSE) { /* Handle error */ }

                return redirect()->back()->with('success', 'Email updated and Invoice shared on email provided');
            }
            else
            {
                return redirect()->back()->with('error', 'Something went wrong, cant share invoice on email. Please try again later.');
            }
        }
    }

    public function sendcustomerrefundinvoice(Request $request)
    {
        $validator = validator::make($request->all(),[
        'customeremail'=>'required'
        ],[
            'customeremail.required'=>'Customer email address is required.'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else
        {
            $updateemail = customer::where('customerID', $request->input('customerid'))->first();
            $updateemail->customeremail = $request->input('customeremail');
            $updateemail->save();

            if($updateemail->save())
            {
                $url = 'https://api.elasticemail.com/v2/email/send';
                $data = array('from' => 'ankit23482@yahoo.com',
                    'fromName' => 'Vodafone Store',
                    'apikey' => '7C8CD416EB4A5D6558682A5EB54D9E7E3A2EA1ADB2D950CC49461A1DED2A2ED86342326B0AEFB27771B99E81A6DC4654',
                    'subject' => 'Purchase Invoice',
                    'to' => $request->input('customeremail'),
                    'bodyHtml' => 'Hello this is an example email',
                    'isTransactional' => false);
                
                // use key 'http' even if you send the request to https://...
                $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data)
                    )
                );
                
                $context  = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                if ($result === FALSE) { /* Handle error */ }

                return redirect()->back()->with('success', 'Email updated and Invoice shared on email provided');
            }
            else
            {
                return redirect()->back()->with('error', 'Something went wrong, cant share invoice on email. Please try again later.');
            }
        }
    }
}
