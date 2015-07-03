<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<title>Calctool - Account activatie</title>
	</head>

	<body style="margin:0; margin-top:30px; margin-bottom:30px; padding:0; width:100%; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; background-color: #F4F5F7;">


		<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border:0; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color: #F4F5F7;">
			<tbody>
				<tr>
					<td align="center" style="border-collapse: collapse;">

						<!-- ROW LOGO -->
						<table cellpadding="0" cellspacing="0" border="0" width="560" style="border:0; border-collapse:collapse; background-color:#ffffff; border-radius:6px;">
							<tbody>
								<tr>
									<td style="border-collapse:collapse; vertical-align:middle; text-align center; padding:20px;">

										<!-- Headline Header -->
										<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
											<tbody>

												<tr><!-- logo -->
													<td width="100%" style="font-family: helvetica, Arial, sans-serif; font-size: 18px; letter-spacing: 0px;">
														<a href="#" style="text-decoration: none;">
															<img src="/images/logo2.png" alt="" border="0" width="166" height="auto" style="with: 166px; height: auto; border: 5px solid #ffffff;">
														</a>
													</td>
												</tr>
												<tr><!-- spacer before the line -->
													<td width="100%" height="20"></td>
												</tr>
												<tr><!-- line -->
													<td width="100%" height="1" bgcolor="#d9d9d9"></td>
												</tr>
												<tr><!-- spacer after the line -->
													<td width="100%" height="30"></td>
												</tr>
												<tr>
													<td width="100%" style="font-family:helvetica, Arial, sans-serif; font-size: 13px; text-align: left; color:#2E363F; line-height: 24px;">
														Beste <strong>{{ ucfirst($username) }}</strong>,
													</td>
												</tr>
												<tr>
													<td width="100%" height="10"></td>
												</tr>
												<tr>
													<td width="100%" style=" font-size: 14px; line-height: 24px; font-family:helvetica, Arial, sans-serif; text-align: left; color:#87919F;">
														Lorem ipsum dolor sit amet, consectetur adipis icing elit, sed do eiusmod tempor
														incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam. Duis aute
														irure dolor in repreh enderit in voluptate velit esse cill fugiat nulla pariatur.
														Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt
														mollit anim id est laborum. Kortom klik op activeren
													</td>
												</tr>
												<tr><!-- spacer after the line -->
													<td width="100%" height="20"></td>
												</tr>
												<tr>
													<!--<td width="100%" height="15"></td>-->
													<td width="100%" style="text-align:center;">
														<a href="{{ URL::to('confirm/' . $api . '/' . $token) }}" style="text-decoration:none; font-family: helvetica, Arial, sans-serif; font-size: 12px; letter-spacing: 0px; text-align: center; text-transform: uppercase; padding:10px; color:#ffffff; background-color:#F07057; border-radius:6px;">
															Activeer account
														</a>
													</td>
												</tr>
												<tr><!-- spacer after the line -->
													<td width="100%" height="20"></td>
												</tr>
												<tr>
													<td width="100%" style="font-family:helvetica, Arial, sans-serif; font-size: 14px; text-align: left; color:#87919F; line-height: 24px;">
														Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
													</td>
												</tr>
											</tbody>
										</table>
										<!-- /Headline Header -->

									</td>
								</tr>
							</tbody>
						</table>
						<!-- /ROW LOGO -->

						<!-- Space -->
						<table width="100%" border="0" cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
							<tbody>
								<tr>
									<td width="100%" height="30"></td>
								</tr>
							</tbody>
						</table>
						<!-- /Space -->

						<!-- ROW FOOTER -->
						<table cellpadding="0" cellspacing="0" border="0" width="560" style="border:0; border-collapse:collapse; background-color:#ffffff; border-radius:6px;">
							<tbody>
								<tr>
									<td style="border-collapse:collapse; vertical-align:middle; text-align center; padding:20px;">

										<!-- copyright-->
										<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
											<tbody>
												<tr><!-- copyright -->
													<td width="100%" style="font-family: helvetica, Arial, sans-serif; font-size: 11px; text-align: center; line-height: 24px;">
														<center>Copyright &copy; {{ date('Y') }} Calctool.nl. Alle rechten voorbehouden.</center>
													</td>
												</tr>
											</tbody>
										</table>
										<!-- /copyright -->


									</td>
								</tr>
							</tbody>
						</table>
						<!-- /ROW FOOTER -->


					</td>
				</tr>
			</tbody>
		</table>

	</body>
</html>
