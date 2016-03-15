@section('actual_content')
	<!-- <div class="container-fluid">
		<div class="row-fluid">
			<div class="block span12" style="border: none !important;">
				<div class="stats">
		            <p class="stat"><span class="number"><?php //echo $close; ?></span> Closed</p>
		            <p class="stat"><span class="number"><?php //echo $waiting; ?></span> Waiting Response</p>
		            <p class="stat"><span class="number">xx</span> email</p>
		        </div>
			</div>
		</div>
	</div>

	<div class="container-fluid">
        <div class="row-fluid">
            <div class="row-fluid">
                <div class="block span6">
                    <p class="block-heading">FAQ Summary</p>
                    <div id="tablewidget" class="block-body">
                        <table class="table list">
                            <tbody>
                                <tr>
                                    <td><p>Total FAQ</p></td>
                                    <td><p><?php //echo $baris; ?></p></td>
                                </tr>
                                <tr>
                                    <td><p>Total Categories</p></td>
                                    <td><p><?php //echo $total_service;?></p></td>
                                </tr>
                                <tr>
                                    <td><p>Total Subcategories</p></td>
                                    <td><p><?php //echo $total_subservice; ?></p></td>
                                </tr>
                                <tr>
                                    <td><p>Total Problem Solved</p></td>
                                    <td><p><?php //echo $problem_solved; ?></p></td>
                                </tr>
                                <tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="block span6">
                    <a href="#widget1container" class="block-heading" data-toggle="collapse">Informasi Akun </a>
                    <div id="widget1container" class="block-body collapse in">
                        <table class="table list">
                            <tbody>
                                <tr>
                                    <td><p>Nama</p></td>
                                    <td><p><?php //echo $user_info['profile']['full_name'];?></p></td>
                                </tr>
                                <tr>
                                    <td><p>Username</p></td>
                                    <td><p><?php //echo $user_info['profile']['username'];?></p></td>
                                </tr>
                                <tr>
                                    <td><p>Tanggal Lahir</p></td>
                                    <td><p><?php //echo $user_info['profile']['dob'];?></p>
                                </tr>
                                <tr>
                                    <td><p>Jenis Kelamin</p></td>
                                    <td><p><?php //echo $user_info['profile']['gender'];?></p></td>
                                </tr>
                                <tr>
                                    <td><p>Email</p></td>
                                    <td><p><?php //echo $user_info['profile']['email_address'];?></p></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="row-fluid">
                <?php
                    $data = array(
                        array("quote" => "Your customer doesn’t care how much you know until they know how much you care.", "author" => "Damon Richards"),
                        array("quote" => "Your most unhappy customers are your greatest source of learning.", "author" => "Bill Gates"),
                        array("quote" => "Good service is good business.", "author" => "Siebel Ad"),
                        array("quote" => "Don’t try to tell the customer what he wants. If you want to be smart, be smart in the shower. Then get out, go to work and serve the customer!", "author" => "Gene Buckley"),
                        array("quote" => "Get closer than ever to your customers. So close that you tell them what they need well before they realize it themselves.", "author" => "Steve Jobs"),
                        array("quote" => "Customer service is not a department, it’s everyone’s job.", "author" => "Anonymous"),
                    );

                    $numbers = range(0, count($data) - 1);
                    shuffle($numbers);
                    $num = array_slice($numbers, 0, 1);
                    $num = $num[0];
                ?>
                <div class="_quote"><i><span class='_quotation' style='font-size: 50px;'>“</span><?= $data[$num]['quote']; ?><span class='quotation' style='font-size: 30px;'>”</span></i></div>
                <div class="_author">- <?= $data[$num]['author']; ?> -</div>
            </div>
        </div>
    </div>

    <style>
        ._quote {
            width: 90%;
            display: block;
            position: relative;
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            padding: 25px;
            color: #4e4e4e;
            line-height: 35px;
        }

        ._author {
            width: 90%;
            display: block;
            position: relative;
            font-size: 20px;
            color: #B0B0B0;
            padding: 0px 25px;
            text-align: center;
        }

        ._quotation {
            font-family: georgia, serif;
            font-weight: normal;
        }
    </style>
@stop