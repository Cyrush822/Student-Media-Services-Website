<?php
    ob_start();

    require('../includes/dbh.inc.php');


    $music_prod = explode(", ", "Ian Tam, Enrico Baneza, Luisa Paraguassu, Simon Fernandez, Kevin Lee");
    $UH = explode(", ", "Alisa Zheng, Henie Zhang, Kelly Kim, Jaclyn Wong, Nicole Qiu, Mamie Yang, Emma Feng, Allison Chen, Finna Wang, Sophia Chak, Daphne Sng, Anna Jin, Ashley Yu, Desiree Sng, Annie Lee, Heewon Chang, Emily Pan, Liz Yim, Aliyah He, Elaine Gu, Hoon Kim");
    $ML = explode(", ", "Isabel Carter, Jonathan Yang, Oscar Yasunaga, Lucas Ren, Charles Yang, Robert Sun, Gerry Ma");
    $VG = explode(", ", "Charles Wang, Doris Wang, Ellie Wang, Geo Chen, Henie Zhang, Isabella Yu, Jaclyn Wong, Jennifer Seo, Jessie Son, Jinny (Jihyun) Chun, Jiwon Lee, Joyce Wang, Julia Purwadi, Mamie Yang, Mindy Wang, Natalie Yang, Sally (Seeun) Kim, Sarah Kim, Sophia Chak, Sophia Xin, JingXi Yap, Athena Ru, Andrew Zhang");
    $Film = explode(", ", "Vivien Yeung, Felipe Paraguassu, Winston Jian, Joe Liang, Ray Cao, Tony Chan, Nelson Lou, Alan Deutsch");
    $Gamedev = explode(", ", "Cyrus Hung, Matthew Chan, Charles Yang, Kenneth Qian, Jessica Luo, Garrette Tsang, Lucas Ren, Shoyo Ko, Oscar Yasunaga, Louis Tszi, Cayden Chng, Daniel Zhao, Shaun Ng, John Yang");
    $DMS = explode(", ", "Heewon Chang, Julia Maeng, Liz Yim, Daphne Sng, Joe Liang, Cindy Hyun, Allison Dai");
    $Livestream = explode(", ", "Ryan Su, Dylan Sun, George Xu, Geon Kim, JaeRyun Shin, Jaden Mak, Junyeong Song, Kevin Jang, Kevin Park, Samuel Chen, Casey Lim, Jongsun Park, Nathan Choi, Jay Shin, Kylie Borg, Hoon Kim");

    $all_people = array($music_prod, $UH, $ML, $VG, $Film, $Gamedev, $DMS, $Livestream);
    $sigs = array("Music Production", "United Herald", "Machine Learning", "Visual Graphics", "Filmmaking", "Game Dev", "DMS", "Live Stream");
    // print_r($people);

    use setasign\Fpdi\Fpdi;
    require_once('fpdf/fpdf.php');
    require_once('fpdi/src/autoload.php');

    $pdf = new Fpdi('L','mm',array(215,279));
    //Add fonts
    $pdf->AddFont('HoboStd');
    $pdf->AddFont('RosewoodStd-Regular');
    $pdf->AddFont('SnellRoundhand-BoldScript');
    $pdf->AddFont('StencilStd');

    // $people_in_multiple_sigs must correspond to $multiple_sigs
    $people_in_multiple_sigs = explode(", ", "Joe Liang, Jaclyn Wong, Mamie Yang, Henie Zhang, Heewon Chang, Daphne Sng, Hoon Kim");
    $multiple_sigs = explode(", ", "Filmmaking and DMS, VG and UH, VG and UH, VG and UH, DMS and UH, DMS and UH, Live Stream and UH");

    $service_hrs_arr = array("Joe Liang"=>array("Filmmaking","DMS",11,0), "Jacyln Wong"=>array("VG","UH",14,2), "Mamie Yang"=>array("VG","UH",12,2), "Henie Zhang"=>array("VG","UH",11,12), "Heewon Chang"=>array("DMS","UH",3,6), "Daphne Sng"=>array("DMS","UH",3,4), "Hoon Kim"=>array("Live Stream","UH",5,2));

    foreach($service_hrs_arr as $member=>$service_hrs) {
        $sig1 = $service_hrs_arr[$member][0];
        $sig2 = $service_hrs_arr[$member][1];
        $service_hrs1 = $service_hrs_arr[$member][2];
        $service_hrs2 = $service_hrs_arr[$member][3];

        // certificate 1
        $pdf->AddPage();
        // set the source file
        $pdf->setSourceFile('master4.pdf');
        // import page 1
        $tplIdx = $pdf->importPage(1);
        // use the imported page and place it at position 10,10 with a width of 100 mm
        $pdf->useTemplate($tplIdx, 0, 0);

        // now write some text above the imported page
        $pdf->SetFont('SnellRoundhand-BoldScript','U',50);
        $pdf->SetTextColor(72, 124, 159);
        $pdf->SetY(80);
        $pdf->Cell(0, 0, $member, 0, 1, 'C');


        $pdf->SetFont('StencilStd','U',17);
        $pdf->SetTextColor(92, 152, 194);

        if ($service_hrs1) {
            $pdf->SetXY(49-strlen($sig1)*1.7,110);
            $msg1 = "For";
            $msg2 = "hours in recognition of their service in";
            $pdf->Cell((strlen($msg1)+1)*3.65, 0, $msg1, 0, 0);
            $pdf->SetTextColor(220, 184, 62);

            // service_hours
            $pdf->Cell((strlen($service_hrs1)+1)*3.65, 0, $service_hrs1, 0, 0, 'C');
            $pdf->SetTextColor(92, 152, 194);
            $pdf->Cell((strlen($msg2)+1)*3.65, 0, $msg2, 0, 0);

        }
        else {
            $pdf->SetXY(66-strlen($sig1)*1.7,110);
            $participation_msg = "In recognition of their participation in";
            $pdf->SetTextColor(92, 152, 194);
            $pdf->Cell((strlen($participation_msg)+1)*3.65, 0, $participation_msg, 0, 0);

        }

        //Add sig name
        $pdf->SetTextColor(220, 184, 62);
        $pdf->Cell(0, 0, $sig1, 0, 0);

        $pdf->SetFont('StencilStd','U',22);
        $pdf->SetTextColor(220, 184, 63);
        $pdf->SetXY(218, 113);


        $pdf->Image('henie.PNG',30,160,70,0,'PNG');
        $pdf->Image('lav.PNG',170,162,70,0,'PNG');

        // certificate 2
        $pdf->AddPage();
        // set the source file
        $pdf->setSourceFile('master4.pdf');
        // import page 1
        $tplIdx = $pdf->importPage(1);
        // use the imported page and place it at position 10,10 with a width of 100 mm
        $pdf->useTemplate($tplIdx, 0, 0);

        // now write some text above the imported page
        $pdf->SetFont('SnellRoundhand-BoldScript','U',50);
        $pdf->SetTextColor(72, 124, 159);
        $pdf->SetY(80);
        $pdf->Cell(0, 0, $member, 0, 1, 'C');


        $pdf->SetFont('StencilStd','U',17);
        $pdf->SetTextColor(92, 152, 194);

        if ($service_hrs2) {
            $pdf->SetXY(49-strlen($sig2)*1.7,110);
            $msg1 = "For";
            $msg2 = "hours in recognition of their service in";
            $pdf->Cell((strlen($msg1)+1)*3.65, 0, $msg1, 0, 0);
            $pdf->SetTextColor(220, 184, 62);

            // service_hours
            $pdf->Cell((strlen($service_hrs2)+1)*3.65, 0, $service_hrs2, 0, 0, 'C');
            $pdf->SetTextColor(92, 152, 194);
            $pdf->Cell((strlen($msg2)+1)*3.65, 0, $msg2, 0, 0);

        }
        else {
            $pdf->SetXY(66-strlen($sig2)*1.7,110);
            $participation_msg = "In recognition of their participation in";
            $pdf->SetTextColor(92, 152, 194);
            $pdf->Cell((strlen($participation_msg)+1)*3.65, 0, $participation_msg, 0, 0);

        }

        //Add sig name
        $pdf->SetTextColor(220, 184, 62);
        $pdf->Cell(0, 0, $sig2, 0, 0);

        $pdf->SetFont('StencilStd','U',22);
        $pdf->SetTextColor(220, 184, 63);
        $pdf->SetXY(218, 113);


        $pdf->Image('henie.PNG',30,160,70,0,'PNG');
        $pdf->Image('lav.PNG',170,162,70,0,'PNG');

    }


    for ($i=0; $i < count($all_people); $i++) {
        $sig = $sigs[$i];

        $members_in_sigs = $all_people[$i];
        foreach ($members_in_sigs as $member) {

            if (!in_array($member, $people_in_multiple_sigs)) {
                // add a page
                $pdf->AddPage();
                // set the source file
                $pdf->setSourceFile('master4.pdf');
                // import page 1
                $tplIdx = $pdf->importPage(1);
                // use the imported page and place it at position 10,10 with a width of 100 mm
                $pdf->useTemplate($tplIdx, 0, 0);

                // now write some text above the imported page
                $pdf->SetFont('SnellRoundhand-BoldScript','U',50);
                $pdf->SetTextColor(72, 124, 159);
                $pdf->SetY(80);

                $pdf->Cell(0, 0, $member, 0, 1, 'C');
                if (mysqli_num_rows(mysqli_query($connection, "SELECT name FROM students WHERE name LIKE '$member';"))) {
                    $sql = "SELECT * FROM
                        (
                        SELECT s.studentid, s.name
                        FROM students AS s
                        WHERE s.name = '$member'
                        ) AS t1
                        LEFT JOIN
                        (
                        SELECT p1.projectid, p1.project_name, p1.datetime_start, p2.studentid, p2.role, p2.service_hours
                        FROM project_list as p1
                        RIGHT JOIN students_in_projects AS p2 ON p1.projectid = p2.projectid
                        WHERE p1.datetime_start LIKE '2020%' OR p1.datetime_start LIKE '2021%'
                        ) AS t2
                        ON t1.studentid = t2.studentid;";

                    $stats = array();
                    $result = mysqli_query($connection, $sql);
                    while($row = mysqli_fetch_assoc($result)) {
                        $stats[] = $row;
                    }

                    $count_hours = array();
                    for ($x=0; $x<count($stats); $x++) {
                        array_push($count_hours, $stats[$x]['service_hours']);
                    }
                    $service_hrs = array_sum($count_hours);

                }
                else {
                    $service_hrs = 0;
                }

                $pdf->SetFont('StencilStd','U',17);
                $pdf->SetTextColor(92, 152, 194);

                if ($service_hrs) {
                    $pdf->SetXY(49-strlen($sig)*1.7,110);
                    $msg1 = "For";
                    $msg2 = "hours in recognition of their service in";
                    $pdf->Cell((strlen($msg1)+1)*3.65, 0, $msg1, 0, 0);
                    $pdf->SetTextColor(220, 184, 62);

                    // service_hours
                    $pdf->Cell((strlen($service_hrs)+1)*3.65, 0, $service_hrs, 0, 0, 'C');
                    $pdf->SetTextColor(92, 152, 194);
                    $pdf->Cell((strlen($msg2)+1)*3.65, 0, $msg2, 0, 0);

                }
                else {
                    $pdf->SetXY(66-strlen($sig)*1.7,110);
                    $participation_msg = "In recognition of their participation in";
                    $pdf->SetTextColor(92, 152, 194);
                    $pdf->Cell((strlen($participation_msg)+1)*3.65, 0, $participation_msg, 0, 0);

                }

                //Add sig name
                $pdf->SetTextColor(220, 184, 62);
                $pdf->Cell(0, 0, $sig, 0, 0);

                $pdf->SetFont('StencilStd','U',22);
                $pdf->SetTextColor(220, 184, 63);
                $pdf->SetXY(218, 113);



                $pdf->Image('henie.PNG',30,160,70,0,'PNG');
                $pdf->Image('lav.PNG',170,162,70,0,'PNG');

                // No longer need second page
                /*
                $pdf->AddPage();
                // set the source file
                $pdf->setSourceFile('master4.pdf');
                // import page 2
                $tplIdx = $pdf->importPage(2);
                // use the imported page and place it at position 10,10 with a width of 100 mm
                $pdf->useTemplate($tplIdx, 0, 0);
                */
            }

        }


    }

    // initiate FPDI

    $pdf->Output('I', 'certificates.pdf');
    ob_end_flush();
?>
