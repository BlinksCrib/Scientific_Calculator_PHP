<!DOCTYPE html>
<html>

<head>
	<title>Simple Scientific Calculator</title>
	<style>
		.btn-primary {
			margin-right: 4px;
			margin-top: 8px;
			width: 100px;
			background-color: #337ab7;
			border-color: #2e6da4;
			color: #fff;
			border-radius: 5px;
		}

		.btn-primary:hover {
			background-color: #286090;
			border-color: #204d74;
			color: #fff;
		}

		.answer {
			margin: 6px;
			box-shadow: 20px;
			font: 20px;
			background-color: #f5f5f5;
			padding: 10px;
			border-radius: 5px;
		}

		b {
			font-size: 30px;
		}

		.mydiv {
			background-color: #f2f2f2;
			padding: 10px;
			border: 1px solid #e6e6e6;
			margin: 20px;
			border-radius: 10px;
		}

		.answer {
			background-color: #e6e6e6;
			padding: 10px;
			border-radius: 50px;
		}
	</style>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css"
		href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body style="background-color:lightblue;">
	<div class='container'>
		<div class="col-md-12">
			<div class="row shadow p-3 mb-5 bg-white rounded mydiv">
				<h1><b>Simple Scientific Calculator</b></h1>
				<form method="post" action="" class="">
					<aside>
						<input type="text" class='form-control' name="expression" placeholder="Enter expression"
							value="<?= isset($_POST['expression']) ? htmlspecialchars($_POST['expression']) : '' ?>">
						<br>
						<button class='btn btn-primary' type="submit" name="calculate">Calculate</button>
						<button class='btn btn-primary' type="button" onclick="appendToExpression('+')">+</button>
						<button class='btn btn-primary' type="button" onclick="appendToExpression('-')">-</button>
						<button class='btn btn-primary' type="button" onclick="appendToExpression('*')">*</button>
						<button class='btn btn-primary' type="button" onclick="appendToExpression('/')">/</button>
						<button class='btn btn-primary' type="button" onclick="appendToExpression('%')">%</button>
						<button class='btn btn-primary' type="button"
							onclick="appendToExpression('sqrt(')">Sqrt()</button>
						<button class='btn btn-primary' type="button"
							onclick="appendToExpression('sin(')">Sin()</button>
						<button class='btn btn-primary' type="button"
							onclick="appendToExpression('cos(')">Cos()</button>
						<button class='btn btn-primary' type="button"
							onclick="appendToExpression('tan(')">Tan()</button>
						<button class='btn btn-primary' type="button"
							onclick="appendToExpression('log(')">Log()</button>
						<button class='btn btn-primary' type="button"
							onclick="appendToExpression('log10(')">Log10()</button>
						<button class='btn btn-primary' type="button"
							onclick="appendToExpression('pow(')">Pow()</button>
						<button class='btn btn-primary' type="button" onclick="clearExpression()">Clear</button>
						<button class='btn btn-primary' type="submit" id="expression">=</button>
					</aside>
				</form>
				<br>

				<div class='answer'>
					<aside class="d-flex">
						<?php
						function customSin($degrees)
						{
							return sin(deg2rad($degrees));
						}

						function customCos($degrees)
						{
							return cos(deg2rad($degrees));
						}

						function customTan($degrees)
						{
							return tan(deg2rad($degrees));
						}

						function sanitizeExpression($expr)
						{
							// Remove any characters that aren't numbers, operators, or allowed function names
							$expr = preg_replace('/[^0-9+\-*\/%.(),\s]/', '', $expr);
							return $expr;
						}

						function evaluateExpression($expr)
						{
							try {
								// Extract any trigonometric functions and their arguments
								if (preg_match_all('/(sin|cos|tan)\(([\d.+-]+)\)/', $expr, $matches)) {
									for ($i = 0; $i < count($matches[0]); $i++) {
										$func = $matches[1][$i];
										$angle = eval ('return ' . $matches[2][$i] . ';');

										// Calculate the trigonometric value
										switch ($func) {
											case 'sin':
												$result = customSin($angle);
												break;
											case 'cos':
												$result = customCos($angle);
												break;
											case 'tan':
												$result = customTan($angle);
												break;
										}

										// Replace the function call with its result
										$expr = str_replace($matches[0][$i], $result, $expr);
									}
								}

								// Now evaluate the rest of the expression
								$result = @eval ('return ' . $expr . ';');

								if ($result === false || $result === null) {
									throw new Exception("Invalid expression");
								}

								return round($result, 6); // Round to 6 decimal places for cleaner output
							} catch (Exception $e) {
								return "Error: " . $e->getMessage();
							}
						}

						if (isset($_POST['expression']) && !empty($_POST['expression'])) {
							$expression = $_POST['expression'];
							$result = evaluateExpression($expression);

							echo "<b>Expression: " . htmlspecialchars($expression) . "</b><br>";
							echo "<b>Answer: " . htmlspecialchars($result) . "</b>";
						}
						?>
					</aside>
				</div>
			</div>
		</div>
	</div>

	<script>
		function appendToExpression(value) {
			var input = document.getElementsByName('expression')[0];
			input.value += value;
		}

		function clearExpression() {
			document.getElementsByName('expression')[0].value = '';
		}

		 // Add event listener for Enter key
		 document.getElementById('expression').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                evaluateExpression();
            }
        });
	</script>
</body>

</html>