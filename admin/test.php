<?php 
                                
                                  include('../admin/pdf/phpinvoice.php');
                                  $invoice = new phpinvoice();
                                  /* Header Settings */
                                  $invoice->setLogo("../img/header.png");
                                  $invoice->setColor("#AA3939");
                                  $invoice->setType("Faktura");
                                  $invoice->setReference("INV-55033645");
                                  $invoice->setDate(date('d-m-Y',time()));
                                  $invoice->setDue(date('d-m-Y',strtotime('+1 months')));
                                  $invoice->setFrom(array("LapKom","LapKom Sp. z o.o.","ul. Sienkiewicza 4","02-366 Warszawa","NIP: 525 24 10 088"));
                                  $invoice->setTo(array("Purchaser Name","Sample Company Name","128 AA Juanita Ave","Glendora , CA 91740","United States of America"));
                                  /* Adding Items in table */
                                  $invoice->addItem("AMD Athlon X2DC-7450","2.4GHz/1GB/160GB/SMP-DVD/VB",6,0,580,0,3480);
                                  $invoice->addItem("PDC-E5300","2.6GHz/1GB/320GB/SMP-DVD/FDD/VB",4,0,645,0,2580);
                                  $invoice->addItem('LG 18.5" WLCD',"",10,0,230,0,2300);
                                  $invoice->addItem("HP LaserJet 5200","",1,0,1100,0,1100);
                                  /* Add totals */
                                  $invoice->addTotal("Suma",9460);
                                  $invoice->addTotal("VAT 23%",1986.6);
                                  $invoice->addTotal("Razem",11446.6,true);
                                  /* Set badge */ 
                                  $invoice->addBadge("Kopia");
                                  /* Set footer note */
                                  $invoice->setFooternote("LapKom Sp. z o.o.");
                                  /* Render */
                                  $invoice->render('example2.pdf','I'); /* I => Display on browser, D => Force Download, F => local path save, S => return document path */
                          
                            ?>