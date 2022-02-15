import './App.css';
import logo from './img/kmp-logo.webp';

import deco from './img/kmp-deco-min.jpg';

import React, {useState, useEffect} from 'react';
import { Field, Formik, Form, ErrorMessage } from 'formik';
import { DisplayFormikState } from './displayFormikState'
import { number, object, string, boolean } from 'yup';

import { Box, Button, Card, CardContent, CircularProgress, Grid, Step, StepLabel, Stepper, Typography, Slider, Radio, FormControl, FormLabel, FormControlLabel, RadioGroup } from '@mui/material';
import { TextField, Checkbox } from 'formik-mui';

import { experimentalStyled as styled, createTheme, ThemeProvider } from "@mui/material/styles";

import ArrowRightAltSharpIcon from '@mui/icons-material/ArrowRightAltSharp';
import RamenDiningSharpIcon from '@mui/icons-material/RamenDiningSharp';
import FaceSharpIcon from '@mui/icons-material/FaceSharp';

import {Helmet} from "react-helmet";

import Cookies from "js-cookie";

//import { blue, grey } from '@mui/material/colors';

//const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

const theme = createTheme({
  	breakpoints: {
    	values: {
      		xs: 0,
      		sm: 640,
      		md: 850,
      		lg: 1200,
      		xl: 1536,
    	},
  	},
});

const CustomizedSlider = styled(Slider)`
	color: #20b2aa;

	transition: all .2s ease-in-out;

	:hover {
		color: #2e8b57;
	}
`;

const sliderMarks = [
	{
		value: 1200,
		label: '1200 Cal',
	},
	{
		value: 1300,
		label: '',
	},
	{
		value: 1400,
		label: '',
	},
	{
		value: 1500,
		label: '',
	},
	{
		value: 1600,
		label: '',
	},
	{
		value: 1700,
		label: '',
	},
	{
		value: 1800,
		label: '',
	},
	{
		value: 1900,
		label: '',
	},
	{
		value: 2000,
		label: '',
	},
	{
		value: 2100,
		label: '',
	},
	{
		value: 2200,
		label: '',
	},
	{
		value: 2300,
		label: '',
	},		
	{
		value: 2400,
		label: '',
	},
	{
		value: 2500,
		label: '2500 Cal',
	},			
];

//const lightGrey = grey[100];

class Asterisk extends React.Component {

	// Add body classes upon component mount
  	componentDidMount() {

		const body = document.body;
		body.classList.add('kmpAppLoaded');
		body.classList.add('kmpAppDidMount');

  	}

	render() {

		return (

			<Box
				className="svgWrap"
			>

				<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="asterisk" className="svg-inline--fa fa-asterisk fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M478.21 334.093L336 256l142.21-78.093c11.795-6.477 15.961-21.384 9.232-33.037l-19.48-33.741c-6.728-11.653-21.72-15.499-33.227-8.523L296 186.718l3.475-162.204C299.763 11.061 288.937 0 275.48 0h-38.96c-13.456 0-24.283 11.061-23.994 24.514L216 186.718 77.265 102.607c-11.506-6.976-26.499-3.13-33.227 8.523l-19.48 33.741c-6.728 11.653-2.562 26.56 9.233 33.037L176 256 33.79 334.093c-11.795 6.477-15.961 21.384-9.232 33.037l19.48 33.741c6.728 11.653 21.721 15.499 33.227 8.523L216 325.282l-3.475 162.204C212.237 500.939 223.064 512 236.52 512h38.961c13.456 0 24.283-11.061 23.995-24.514L296 325.282l138.735 84.111c11.506 6.976 26.499 3.13 33.227-8.523l19.48-33.741c6.728-11.653 2.563-26.559-9.232-33.036z"></path></svg>

			</Box>

		)

	}

}

class PersonalDetailsHeader extends React.Component {

	render() {

		return (

			<React.Fragment>

				<Card 
					 className={'step-' + (this.props.step + 1) + '--headerContent'}
				>
					
					<CardContent
						className="cardContent--stepHeaderContent">	

					  	<Box 
					  		sx={{ 
					  			display: 'flex', 
					  			flexDirection: 'row', 
					  			justifyContent: 'space-between', 
					  			alignItems: 'center',
					  			pt: 2 }}
					  		className="stepHeader"
					  	>
					    
						  	<Box 
						  		className="stepHeader__e1"
							  	sx={{ flexGrow: 1, display: 'flex', flexDirection: 'row', justifyContent: 'flex-start', pt: 2 }}
							  	>

							  	<Box 
							  		className="stepHeader__iconWrap"
								  	>

								  	<FaceSharpIcon />

								</Box> 		  	

								<Typography 
									className="kmp-label--step-heading" 
									gutterBottom
									variant="h3"
									component="h3"	
									sx={{ flexGrow: 1 }}
								>
									Personal Details
								</Typography> 

							</Box> 								    	
					  
					  	</Box>	

				  	</CardContent>

				</Card>					  	

			</React.Fragment>	

		)

	}

}

class MealPreferencesHeader extends React.Component {

	render() {

		return (

			<React.Fragment>

				<Card 
					 className={'step-' + (this.props.step + 1) + '--headerContent'}
				>
					
					<CardContent
						className="cardContent--stepHeaderContent">	

						  	<Box 
						  		sx={{ 
						  			display: 'flex', 
						  			flexDirection: 'row', 
						  			justifyContent: 'space-between', 
						  			alignItems: 'center',
						  			pt: 2 }}
						  		className="stepHeader"
						  	>
						    
							  	<Box 
							  		className="stepHeader__e1"
								  	sx={{ flexGrow: 1, display: 'flex', flexDirection: 'row', justifyContent: 'flex-start', pt: 2 }}
								  	>

								  	<Box 
								  		className="stepHeader__iconWrap"
									  	>

									  	<RamenDiningSharpIcon />

									</Box> 		  	

									<Typography 
										className="kmp-label--step-heading" 
										gutterBottom
										variant="h3"
										component="h3"	
									>
										Meal Preferences
									</Typography> 

								</Box> 	

								<Button
								  	/*disabled={Formik.isSubmitting}*/
								  	variant="contained"
								  	color="primary"
								  	onClick={this.props.onClickCallback}
								  	className="stepHeader_e3 backButton"
								  	startIcon={<ArrowRightAltSharpIcon />}
								  	sx={{ flexGrow: 0 }}
								>
							  		Go Back
								</Button>							    	
						  
						  	</Box>	

				  	</CardContent>

				</Card>					  	

			</React.Fragment>		

		)

	}

}

const steps = ['Step 1 of 2', 'Step 2 of 2'];

/* eof TEST COOKIE DEFINITION */

export default function HorizontalLinearStepper() {

	const [activeStep, setActiveStep] 	= useState(0);
  	//const [completed, setCompleted] 	= useState(false);

	const handleNext = (requiredFieldsTouched, requiredFieldsValid, isDevMode) => {

		if ( 
				( true === isDevMode ) ||
				(
					requiredFieldsTouched && 
					requiredFieldsValid 
				)
			)
			setActiveStep((prevActiveStep) => prevActiveStep + 1);
  	
  	};

  	const handleBack = () => {
    	setActiveStep((prevActiveStep) => prevActiveStep - 1);
  	};

	let test_isDevMode 			= false,
		test_isLoggedIn 		= false,
		test_isPlatinumMember 	= false,
		test_withCookies 		= false,
		onLocalhost 			= (
									window.location.hostname === 'localhost' || 
									window.location.hostname === '127.0.0.1'
								  );

	const body 					= document.body,
		  isDevMode				= (
				  					body.classList.contains('devMode') ||
				  					( test_isDevMode === true )
				  				  ),
		  isLoggedIn 			= (
				  					body.classList.contains('logged-in') || 
				  					( test_isLoggedIn === true )
				  				  ),
		  isPlatinumMember 		= (
				  					body.classList.contains('isPlatinumMember') ||
				  					( test_isPlatinumMember === true )
				  				  ),
		  withCookies 			= (
				  					body.classList.contains('withCookies') ||
				  					( test_withCookies === true )
				  				  ),
		  // The number of form submissions allowed before disabling the submit button 
		  // and functionality.
		  restrictNum 			= body.classList.contains('withCookies')
		  							? typeof kmp === 'undefined'
		  								? null
		  								: parseInt( kmp.restrictNum )
		  							: 2,
		  // The cookie expiration number in minutes.		  							
		  ckExpMins 			= body.classList.contains('withCookies')
		  							? typeof kmp === 'undefined' 
		  								? null
		  								: parseInt( kmp.ckExpMins )
		  							: 120,
		  // The expiration time
		  ckExp 				= new Date(new Date().getTime() + ckExpMins * 60 * 1000);

		  //console.log( 'isPlatinumMember value is:' + isPlatinumMember );

	// TEST COOKIE DEFINITION - we only need this while testing the cookie restriction 
	// feature on localhost. 
	// Otherwise the cookie init is handled in the plugin's public JS file. 
	if ( true === test_withCookies && true === onLocalhost ) {

		if ( undefined === Cookies.get('kmp-ck-plannerUsed') )
			Cookies.set('kmp-ck-plannerUsed', 0, { expires: ckExp, sameSite: 'strict' });	

	}

	// If we're not testing the restriction-with-cookies feature, delete all
	// kmp cookies 
	if ( false === test_withCookies && true === onLocalhost ) {
		Cookies.remove('kmp-ck-plannerUsed');	
	}

	console.log(
		( true === withCookies && false === isPlatinumMember ) &&
		( restrictNum === parseInt( Cookies.get('kmp-ck-plannerUsed') ) )
	)

	console.log( withCookies );
	console.log( restrictNum );
	console.log( parseInt( Cookies.get('kmp-ck-plannerUsed') ) );
	console.log( restrictNum === parseInt( Cookies.get('kmp-ck-plannerUsed') ) );
	console.log( ckExpMins );

	const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms))

	// Add body classes upon component mount
	useEffect(() => {

		const body = document.body;
		body.classList.add('kmpAppLoaded');
		body.classList.add('kmpAppDidMount');

  	});	

	return (

			<ThemeProvider theme={theme}>

		    <Box 
		    	id="kmpAppRootBox"
		    	sx={{ 
		    		width: '100%',
		    		maxWidth: 970, 
					marginLeft: 'auto',
					marginRight: 'auto' 		    		
		    	}}
		    >

		        <Helmet>
		              <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		        </Helmet>				    

				<Card 
					 className={'step-' + (activeStep + 1) + '--headerContent'}
				>
					
					<CardContent
						className="cardContent--stepHeaderContent">			    

					<Box
			  			className="kmpLogoWrap"
					  	sx={{ pt: 2 }}								
					>
						<img src={logo} className="kmpLogo" alt="Ketogenic Meal Planner Logo" />
					</Box>		

			  		{/*<Box
			  			className="appIntro"
					  	sx={{ pt: 2 }}
			  		>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud... 								  		
			  		</Box>*/}					    

				</CardContent>

			</Card>

			{ 0 === activeStep && false === isLoggedIn
				? (					

					<PersonalDetailsHeader 
						step={activeStep}
					/>			  	

				) : null
			}   

			{ 1 === activeStep && false === isLoggedIn 
				? (					

					<MealPreferencesHeader 
						step={activeStep}
						onClickCallback={ async (event) => {

							if ( false === isLoggedIn ) {
								handleBack();
							}

						}}
					/>								  	

				) : null
			}  				

			{ false === isLoggedIn
				? (

					<Card 
						className="stepper"
					>
							
						<CardContent
							className="cardContent--muiStepper"
						>		    

					      	<Stepper 
					      		activeStep={1} 
					      		alternativeLabel
					      		className={ 'steps-taken--' + (activeStep + 1) }
					      	>
								
								{
									steps.map((label, index) => {
						          
						          		const stepProps 	= {};
						          		const labelProps 	= {};
						          			          
						          		return (

								            <Step 
								            	key={label} 
								            	{...stepProps} 
								            	completed={false} 
								            	className={ activeStep > index || activeStep === index ? 'active' : 'inactive' }
								            >
								              	<StepLabel {...labelProps}>{label}</StepLabel>
								            </Step>
						          		
						          		);
						        	
						        	})
								}
					      	
							</Stepper>

						</CardContent>

					</Card>

				) : null
			}

			<Formik
				  	// The below value (enableReinitialize) is required to make form validation 
				  	// work for radios, checkboxes and other non-typical form fields!!!
				  	enableReinitialize
				  	// With enableReinitialize, Formik will trigger validation on Blur and on Change. 
				  	// To avoid this you can add validateOnChange and validateOnBlur to false:
				  	//validateOnChange={false}
			   	  	//validateOnBlur={false}
				  	// ----------------------------------------------------------------------
				  	// @here https://stackoverflow.com/questions/63525393/edit-an-entry-with-formik-and-yup-doesnt-recognize-the-existing-fields#answer-63782871
				  	initialValues={{
				  		kmpFieldFirstName: '',
				  		kmpFieldLastName: '',
				  		kmpFieldEmail: '', 
				  		kmpFieldPhone: '', 
				  		//kmpFieldNewsletter: '', 
				  		kmpFieldCalTarget: 1800,
				  		kmpFieldFasting: '',
						kmpFieldMealsNumber: '',
						kmpFieldAddDessert: '',
						kmpFieldDietType1: '',
						kmpFieldSensitivities: '',
						kmpFieldSensitivitiesOther: '',						
						kmpFieldDietType2: '',
						kmpFieldMealComplexity: '',
						kmpFieldYourGoals: '',
				  		kmpFieldAgreement: false,						
					}}
					/* initialTouched values should be specified in the Formik "init", otherwise if a field is not touched, there's no return at all to be used in a button click action */
				  	initialTouched={{
				  		kmpFieldFirstName: false === isLoggedIn ? false : true,
				  		kmpFieldLastName: false === isLoggedIn ? false : true, 
				  		kmpFieldEmail: false === isLoggedIn ? false : true, 
				  		kmpFieldPhone: false,
				  		//kmpFieldNewsletter: '', 
				  		kmpFieldCalTarget: true,
				  		kmpFieldFasting: false,
						kmpFieldMealsNumber: false,
						kmpFieldAddDessert: false,
						kmpFieldDietType1: false,
						kmpFieldSensitivities: false,
						kmpFieldSensitivitiesOther: false,						
						kmpFieldDietType2: false,
						kmpFieldMealComplexity: false,
						kmpFieldYourGoals: false,
						kmpFieldAgreement: false,
					}}					
				  	validationSchema={ !isDevMode ? object({
			   			kmpFieldFirstName: false === isLoggedIn ? string()
														   			.required('Required')
															 		.min(2, 'Too Short!')
																	.max(100, 'Too Long!')
																: string(),
						kmpFieldLastName: false === isLoggedIn ? string()
														   			.required('Required')
															 		.min(2, 'Too Short!')
																	.max(100, 'Too Long!')
																: string(),
			   			kmpFieldEmail: false === isLoggedIn ? string()
																	.email('Invalid email')
													   				.required('Required')
																: string(),
					 	kmpFieldCalTarget: number()
					  		.required("Required")					 	
					  		.min(1200, "The number should be higner than 1200")
					  		.max(2500, "The number should be lower than 2500"),
			   			kmpFieldFasting: string()
			   				.required('This field is required.'),
			   			kmpFieldMealsNumber: string()
			   				.required('This field is required.'),
						kmpFieldAddDessert: string().when('kmpFieldMealsNumber', {
        					is: (kmpFieldMealsNumber) => kmpFieldMealsNumber === '2' || kmpFieldMealsNumber === '3' || kmpFieldMealsNumber === '4',
        					then: string()
			   						.required('This field is required.')
      					}),
			   			kmpFieldDietType1: string()
			   				.required('This field is required.'),
						kmpFieldDietType2: string().when('kmpFieldDietType1', {
        					is: 'no',
        					then: string()
			   						.required('This field is required.')
      					}),			   				
			   			kmpFieldSensitivities: string()
			   				.required('This field is required.'),
			   			kmpFieldMealComplexity: string()
			   				.required('This field is required.'),
			   			kmpFieldAgreement: boolean()
						    .required("The terms and conditions must be accepted.")
						    .oneOf([true], "The terms and conditions must be accepted."),
				  	}) : null }
				  	onSubmit={ async (values, actions) => {

				  		console.log('This is BEFORE the cookie update');

						// Update the formSubmitted cookie value IF the value is less than the
						// restrictNum value
						if ( restrictNum > parseInt( Cookies.get('kmp-ck-plannerUsed') ) ) {

							const x = parseInt( Cookies.get('kmp-ck-plannerUsed') );

							Cookies.remove('kmp-ck-plannerUsed');
							Cookies.set('kmp-ck-plannerUsed', x + 1, { expires: ckExp, sameSite: 'strict' });	
							// alert( Cookies.get('kmp-ck-plannerUsed') );
						
						}

				  		//console.log(Step);
				  		
				  		console.log('This is AFTER the cookie update');

					  	// Add custom body class on successful form submission
						const body = document.body;
						body.classList.add('formSubmitted');

						// Create new event on successfuly form submission, and add it to the 
						// event listener
						const formSubmittedEvent = new CustomEvent(
							'formSubmitted', 
							{
								detail: { 
									firstName: values.kmpFieldFirstName,
									lastName: values.kmpFieldLastName,
									email: values.kmpFieldEmail,	
									phone: values.kmpFieldPhone,
									calTarget: values.kmpFieldCalTarget,
									fasting: values.kmpFieldFasting,
									mealsNumber: values.kmpFieldMealsNumber,
									/* FYKI!!! If the user selects 1 meal per day, there addDessert value should be 'no'!!!
									WHY? Because the project spreadsheet 'Calority Possibilities' tab doesn't include "formulas" for with-dessert or with-snack scenarios when the mealsNumber selection is 1. 
									This way also makes the CPCLASSES method name structuration easier.*/
									addDessert: ( 
													'2' === values.kmpFieldMealsNumber ||
													'3' === values.kmpFieldMealsNumber ||
													'4' === values.kmpFieldMealsNumber 
												)
													? values.kmpFieldAddDessert
													: 'no',
									dietType1: values.kmpFieldDietType1,
									dietType2: values.kmpFieldDietType2,
									sensitivities: values.kmpFieldSensitivities,
									sensitivitiesOther: values.kmpFieldSensitivitiesOther,
									mealComplexity: values.kmpFieldMealComplexity,
									yourGoals: values.kmpFieldYourGoals,
								},
								bubbles: false
							}
						);

						// Dispatch the event
						body.dispatchEvent(formSubmittedEvent);					

						await sleep(1000);
						console.log(JSON.stringify(formSubmittedEvent.detail, null, 2));

					  	//alert('👍 All done!');
					  	actions.setSubmitting(false);

				  	}}
					> 
	       			{props => (	                

						/* The above line is the Formik render method: https://formik.org/docs/api/formik#children-reactreactnode--props-formikpropsvalues--reactnode */

						/* Below: use `noValidate` todisable Chrome's default form validation */

						<Form
							noValidate
							autoComplete="on"
						>

{/**
   * STEP ONE
   * ==============================================================================================
   */}

{ 0 === activeStep && false === isLoggedIn
	? (					

		<React.Fragment>

			<Card 
				 className={'step-' + (activeStep + 1) + ' step--personal-details'}
			>
				
				<CardContent
					className="cardContent--formContent">	

					<Grid 
						container 
						spacing={3.75}
					>

	  					<Grid 
	  						item 
	  						xs={12} 
	  						md={6}
	  					>

							<Grid 
								container 
							  	spacing={{
							    	xs: 0,
							    	md: 2.5
							  	}}
							  	className={'fieldWrapsGrouper'}
							>

			  					<Grid 
			  						item 
			  						xs={12} 
			  						md={6}
			  					>

								  	<Box 
								  		sx={{pb:2}}
								  		className="fieldWrapBox"						  		
								  	>			  					
									
										<Field 
										  	type="text"
										  	name="kmpFieldFirstName" 
										  	component={TextField} 							  		
										  	label="First Name" 
										  	inputProps={{ onChange:props.handleChange }}
										  	value={props.values.kmpFieldFirstName}
										 	fullWidth
										 	required
										 	/* props.touched.[field] should be set on blur so that we can use the updated value in the Next button click action and the handleNext function. 
										 	AND WE NEED THE CUSTOM ONCHANGE EVENT AS WELL, to fix the browser autofill issue => basically, if the user clicks in the kmpFieldFirstName field, and uses the browser's autofill, the kmpFieldFirstName field might get a value as well. As a result neither the props.touched.[kmpFieldFirstName] value would be updated appropriately, nor an onBlur event would happen on the kmpFieldFirstName field before the click on the Next button. The below onChange function fixes this issue by updating the touched value even when an autofill populates the kmpFieldFirstName field, and by validating the field if there's actually an autofilled value.
										 	FYKI: the validateField parameter is AND SHOULD BE set to FALSE in the oncChange function!!! */
										 	onChange={ async (event) => {		
										 		props.setFieldTouched('kmpFieldFirstName', true, false);

										 		if ( '' !== props.values.kmpFieldFirstName )
										 			props.validateField('kmpFieldFirstName');
											}}										 	
											onBlur={ async (event) => {
												props.setFieldTouched('kmpFieldFirstName', true, true);
											}}											 	
										/>

										{
											false === isLoggedIn
												? (

													<Asterisk />

												) : null
										}

									</Box>

							  	</Grid>	

			  					<Grid 
			  						item 
			  						xs={12} 
			  						md={6}
			  					>

								  	<Box 
								  		sx={{pb:2}}
								  		className="fieldWrapBox"						  		
								  	>			  					
									
										<Field 
										  	type="text"
										  	name="kmpFieldLastName" 
										  	component={TextField}	
										  	label="Last Name" 
										  	inputProps={{ onChange:props.handleChange }}
										  	value={props.values.kmpFieldLastName}
										 	fullWidth
										 	required
										 	/* props.touched.[field] should be set on blur so that we can use the updated value in the Next button click action and the handleNext function. 
										 	AND WE NEED THE CUSTOM ONCHANGE EVENT AS WELL, to fix the browser autofill issue => basically, if the user clicks in the kmpFieldLastName field, and uses the browser's autofill, the kmpFieldLastName field might get a value as well. As a result neither the props.touched.[kmpFieldLastName] value would be updated appropriately, nor an onBlur event would happen on the kmpFieldLastName field before the click on the Next button. The below onChange function fixes this issue by updating the touched value even when an autofill populates the kmpFieldLastName field, and by validating the field if there's actually an autofilled value.
										 	FYKI: the validateField parameter is AND SHOULD BE set to FALSE in the oncChange function!!! */
										 	onChange={ async (event) => {		
										 		props.setFieldTouched('kmpFieldLastName', true, false);

										 		if ( '' !== props.values.kmpFieldLastName )
										 			props.validateField('kmpFieldLastName');
											}}										 	
											onBlur={ async (event) => {
												props.setFieldTouched('kmpFieldLastName', true, true);
											}}											 	
										/>

										{
											false === isLoggedIn
												? (

													<Asterisk />

												) : null
										}

									</Box>

							  	</Grid> {/* EOF Grid item */}							  	
						  
						  	</Grid> {/* EOF Grid container */}	 

						  	<Box 
						  		sx={{py:2}}
						  		className="fieldWrapBox"						  		
						  	>
							
								<Field 
								  	type="email"
								  	name="kmpFieldEmail" 
								  	component={TextField} 
								  	label="Email" 
								  	inputProps={{ onChange:props.handleChange }}
								  	value={props.values.kmpFieldEmail}
								  	fullWidth
								 	required
								 	/* props.touched.[field] should be set on blur so that we can use the updated value in the Next button click action and the handleNext function. 
								 	AND WE NEED THE CUSTOM ONCHANGE EVENT AS WELL, to fix the browser autofill issue => basically, if the user clicks in the kmpFieldName field, and uses the browser's autofill, the kmpEmailField field might get a value as well. As a result neither the props.touched.[fieldName] value would be updated appropriately, nor an onBlur event would happen on the kmpFieldEmail field before the click on the Next button. The below onChange function fixes this issue by updating the touched value even when an autofill populates the kmpFieldEmail field, and by validating the field if there's actually an autofilled value.
								 	FYKI: the validateField parameter is AND SHOULD BE set to FALSE in the oncChange function!!! */
								 	onChange={ async (event) => {		
								 		props.setFieldTouched('kmpFieldEmail', true, false);

								 		if ( '' !== props.values.kmpFieldEmail )
								 			props.validateField('kmpFieldEmail');
									}}										 	
									onBlur={ async (event) => {
										props.setFieldTouched('kmpFieldEmail', true, true);
									}}											 								  	
								/>

								{
									false === isLoggedIn
										? (

											<Asterisk />

										) : null
								}
							  	
						  	</Box>

						  	<Box 
						  		sx={{
						  			px:2
						  		}}
						  		className="fieldWrapBox"						  		
						  	>
							
								<Field 
								  	type="text"
								  	name="kmpFieldPhone" 
								  	component={TextField} 
								  	label="Phone" 
								  	inputProps={{ onChange:props.handleChange }}
								  	value={props.values.kmpFieldPhone}
								  	fullWidth
								 	onChange={ async (event) => {		
								 		props.setFieldTouched('kmpFieldPhone', true, false);
									}}										 	
									onBlur={ async (event) => {
										props.setFieldTouched('kmpFieldPhone', true, true);
									}}
								/>
						  	</Box>						  	

							{/* This block fixes the 'Field with {CheckboxWithLabel} component kills the Chrome autofill functionality' bug */}
						   	<input
							 	id="email"
							 	name="email"
							 	type="text"
							 	style={{display:'none'}}
						   	/>  					   	

						   	{/* --------- THIS IS COMMENTED OUT - IT'S JUST THAT THE COLOR SYNTAX IS MESSED UP!!! ---------
						  	<Box sx={{pb:2}}>
							
								<Field  
								  	type="checkbox"
								  	name="kmpFieldNewsletter" 
								  	component={CheckboxWithLabel} 
								  	Label={{ label: 'Yes, I want to subscribe' }} 
								  	// The below 3 lines are BOTH required to make 
								  	// the checkbox work. 
								  	// The onChange function is to remember how 
								  	// setValue should/could work, or to be used,
								  	// theoretically (UNTESTED).
								  	//
								  	// FYKI - use either 
								  	// `inputProps={{ onChange:props.handleChange }}` 
								  	// or the onChange function like below - the two 
								  	// can't be used at the same time, obviously and
								  	// logically.
								  	value={props.values.kmpFieldNewsletter}
								  	inputProps={{ onChange:props.handleChange }}
								  	checked={props.values.kmpFieldNewsletter == true ? true : false}
								  	// onChange={(e) => {
								  	//   	props.setFieldValue(
								  	//   		'kmpFieldNewsletter', 
								  	//   		e.target.checked
								  	//   	);
								  	// }}                      
								/>

								<p id="kmp-errorwrap--newsletter"><ErrorMessage name="kmpFieldNewsletter" /></p>

						  	</Box>   
						  	*/}

						  	<Box 
						  		sx={{ pt: 2 }}
						  		className="buttonWrapBox"						  		
						  	>
						    
							  	<Button
									variant="contained"
									color="primary"
									type="button"
									className="nextButton"
									endIcon={<ArrowRightAltSharpIcon />}
									onClick={ async (event) => {

										if ( false === isLoggedIn ) {

											//console.log(props.touched.kmpFieldFirstName);
											//console.log(props.touched.kmpFieldLastName);
											//console.log(props.touched.kmpFieldEmail);
											//console.log(props.touched.kmpFieldPhone);

											/*Set up constants to be forwarded to the handleNext function.*/
											const requiredFieldsTouched = 
												( 
													props.touched.kmpFieldFirstName &&
													props.touched.kmpFieldLastName && 
													props.touched.kmpFieldEmail  
												)
													? true
													: false;

											const requiredFieldsValid = 
												( 
													props.errors.kmpFieldFirstName ||
													props.errors.kmpFieldLastName || 
													props.errors.kmpFieldEmail  
												)
													? false
													: true;	

											/*If the user didn't touch some of the fields, set all step field touched value to true - this way we can trigger field-level validation upon button click; the handleNext function already has the parameters necessary to "decide" if the form step can be upgraded or further user action is reqired before the pagination.*/
											if ( false === requiredFieldsTouched ) {

												props.setFieldTouched(
													'kmpFieldFirstName', 	// field 
													true,  					// is touched?
													true 					// should validate?
												);
												props.setFieldTouched('kmpFieldLastName', true, true);
												props.setFieldTouched('kmpFieldEmail', true, true);

											}

											//console.log(props);	

											handleNext( requiredFieldsTouched, requiredFieldsValid, isDevMode );

										}

									}}
							  	>
							  		Next
							  	</Button>								    	
					  
						  	</Box>	

						</Grid>

	  					<Grid 
	  						item 
	  						xs={12} 
	  						md={6}
	  					>

							<Box
					  			className="kmpDecoWrap"
							  	sx={{ pt: 2 }}								
							>
								<img src={deco} className="kmpDeco" alt="Ketogenic Meal Planner Decoration" />
							</Box>		    	  						

	  					</Grid>

	  				</Grid>

			  	</CardContent>

		  	</Card>					  	

		</React.Fragment>									  	

	  ) : null

}   

{/**
   * STEP TWO
   * ==============================================================================================
   */}							

{ 
	( 1 === activeStep && false === isLoggedIn ) ||
	( 0 === activeStep && true === isLoggedIn ) 
	? (

		<React.Fragment>	

		<Card 
			className={'step-' + (activeStep + 1) + ' step--meal-preferences'}
		>

			<CardContent
				className="cardContent--formContent"
			>									

		  	<Box 
		  		sx={{pb:2}}
					className="fieldWrapBox"  		
		  	>
			
				<Typography id="kmp-label--calorie-slider" gutterBottom>Select how many calories you’d like your interactive sliding meal plan to follow:</Typography>    

				<Box
					className={'kmpSublabelWrap__calorieSlider kmpSublabelWrap'}
					sx={{
						display: 'flex',
						flexWrap: 'nowrap',
						justifyContent: 'flex-start',
						alignItems: 'stretch',
						alignContent: 'flex-start',
					}}
				>

					<Box
						className={'checkboxWrap'}
						sx={{
							flexGrow: 1,
						}}
					>

						<Typography id="kmp-sublabel--calorie-slider" gutterBottom>Select the calorie content that is closest to your regimented intake or that is closest to the recommendation that our <a href="/calculator/" target="_blank">Keto Calculator</a> provides. Round down if calorie content is in middle (i.e. if your desired intake is 1732 calories, you would choose the 1700 calorie option).</Typography>

					</Box>

					<Box
						className={'checkboxWrap'}
						sx={{
							flexGrow: 0,
						}}
					>

						<a 
							href="/calculator/"
							target="_blank"
							className={'cta'}
						>Need help? Use our<br />Keto Calculator</a>

					</Box>					

				</Box>

				<Box 
					sx={{px: 5, pt: 4, pb: 6}}
					className="sliderWrap"
				>

					<CustomizedSlider
						aria-label="Calorie Target"
						defaultValue={1800}
						step={100}
						marks={sliderMarks}
						min={1200}
						max={2500}
						name="kmpFieldCalTarget"
						inputProps={{ onChange:props.handleChange }}
						value={props.values.kmpFieldCalTarget} 
						valueLabelDisplay="on" 
						onChange={(event, val) => props.setFieldValue("kmpFieldCalTarget", val)}
						//onDragStop={ (e, val) => this.props.update(e, control.id, this.val)}
					/>

				</Box>

				{/* Az errors, touched cuccok (https://formik.org/docs/guides/validation) azért nem hozzáférhetőek, mert jelenleg egy komponens belsejében vagy, és 
				az errors, touched ott hozzáférhetőek csak, ahonnét ez a 
				komponens-belső "meg van idézve". */}

		  	</Box>	

				{/**
			   	   * Radio Group 1 - Do you practice intermittent fasting?
			   	   * ------------------------------------------------------------------
			   	   */}						

		  	<Box 
		  		sx={{pb:2}}
					className="fieldWrapBox fieldWrapBox--radio optionsNum--2"
		  	>

				<FormControl component="fieldset">

			  		<FormLabel 
			  			component="legend" 
			  			required
			  		>
			  			Do you practice intermittent fasting? <span class="required-asterisk">*</span>
						<p id="kmp-errorwrap--fasting"><ErrorMessage name="kmpFieldFasting" /></p>	
			  		</FormLabel>										  		

				  	<RadioGroup 
						row aria-label="fasting" 
						name="kmpFieldFasting"
				   	>

						<FormControlLabel 
						  	control={<Radio />} 
						  	label="No" 
						  	value="no"
						  	checked={props.values.kmpFieldFasting === "no"}
						  	className={ props.values.kmpFieldFasting === "no" ? 'isChecked' : '' }
							/*sx={{
								'& .MuiSvgIcon-root': {
							    	fontSize: 28,
								},
							}}*/	
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldFasting", "no")

						 		props.setFieldTouched('kmpFieldFasting', true, false);

							}}						  	
						/>
					
						<FormControlLabel 
						  	control={<Radio />} 
						  	label="Yes" 
						  	value="yes"
						  	checked={props.values.kmpFieldFasting === "yes"}
						  	className={ props.values.kmpFieldFasting === "yes" ? 'isChecked' : '' }
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldFasting", "yes")

						 		props.setFieldTouched('kmpFieldFasting', true, false);

							}}
						/>

				  	</RadioGroup>

				</FormControl>  
		 
			</Box>
			   	  
				{/**
			   	   * Radio Group 2 - How many full meals do you prefer to eat per day?
			   	   * ------------------------------------------------------------------
			   	   */}
			   	  
		  	<Box 
					className="fieldWrapBox fieldWrapBox--radio  optionsNum--4" 
		  		sx={{pb:2}}
		  	>

				<FormControl component="fieldset">

			  		<FormLabel 
			  			component="legend" 
			  			required
			  		>
			  			How many full meals do you prefer to eat per day? <span class="required-asterisk">*</span>
						<p id="kmp-errorwrap--meals-number"><ErrorMessage name="kmpFieldMealsNumber" /></p>	
			  		</FormLabel>

				  	<RadioGroup 
						row aria-label="meals-number" 
						name="kmpFieldMealsNumber"
				   	>

						<FormControlLabel 
						  	control={<Radio />} 
						  	label="1" 
						  	value="1"
						  	checked={props.values.kmpFieldMealsNumber === "1"}
						  	className={ props.values.kmpFieldMealsNumber === "1" ? 'isChecked' : '' }
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldMealsNumber", "1")

						 		props.setFieldTouched('kmpFieldMealsNumber', true, false);

							}}
						/>
					
						<FormControlLabel 
						  	control={<Radio />} 
						  	label="2" 
						  	value="2"
						  	checked={props.values.kmpFieldMealsNumber === "2"}
						  	className={ props.values.kmpFieldMealsNumber === "2" ? 'isChecked' : '' }				  	
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldMealsNumber", "2")

						 		props.setFieldTouched('kmpFieldMealsNumber', true, false);

							}}
						/>

						<FormControlLabel 
						  	control={<Radio />} 
						  	label="3" 
						  	value="3"
						  	checked={props.values.kmpFieldMealsNumber === "3"}
						  	className={ props.values.kmpFieldMealsNumber === "3" ? 'isChecked' : '' }				  	
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldMealsNumber", "3")

						 		props.setFieldTouched('kmpFieldMealsNumber', true, false);

							}}
						/>										

						<FormControlLabel 
						  	control={<Radio />} 
						  	label="4" 
						  	value="4"
						  	checked={props.values.kmpFieldMealsNumber === "4"}
						  	className={ props.values.kmpFieldMealsNumber === "4" ? 'isChecked' : '' }				  	
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldMealsNumber", "4")

						 		props.setFieldTouched('kmpFieldMealsNumber', true, false);

							}}
						/>										

				  	</RadioGroup>

				</FormControl>  
		 
			</Box>			  			   	  
			   	  
				{/**
			   	   * Radio Group 3 - Do you want your meal plan to include a snack or
			   	   * 				 a dessert?
			   	   * ------------------------------------------------------------------
			   	   */}

			   	{ /* We only display the kmpFieldAddDessert controls if the number of meals selected by the user is 2,3 or 4. Why? Because the project spreadsheet 'Calority Possibilities' tab doesn't include "formulas" for with-dessert or with-snack scenarios when the mealsNumber selection is 1. This way also makes the CPCLASSES method name structuration easier.  */ }
			{ 
				( '2' === props.values.kmpFieldMealsNumber ) ||
			  	( '3' === props.values.kmpFieldMealsNumber ) ||
				( '4' === props.values.kmpFieldMealsNumber )
				? (

				  	<Box 
	  					className="fieldWrapBox fieldWrapBox--radio  optionsNum--3" 
				  		sx={{pb:2}}
				  	>

						<FormControl component="fieldset">

					  		<FormLabel 
					  			component="legend" 
					  			required
					  		>
					  			Do you want your meal plan to include a snack or a dessert? <span class="required-asterisk">*</span>
								<p id="kmp-errorwrap--add-dessert"><ErrorMessage name="kmpFieldAddDessert" /></p>	
					  		</FormLabel>							

						  	<RadioGroup 
								row aria-label="add-dessert" 
								name="kmpFieldAddDessert"
						   	>

								<FormControlLabel 
									id="kmpf--add-dessert--snack"
								  	control={<Radio />} 
								  	label="1 Snack" 
								  	value="snack"
								  	checked={props.values.kmpFieldAddDessert === "snack"}
						  			className={ props.values.kmpFieldAddDessert === "snack" ? 'isChecked' : '' }
								 	onChange={ async (event) => {		

										props.setFieldValue("kmpFieldAddDessert", "snack");

								 		props.setFieldTouched('kmpFieldAddDessert', true, false);

									}}      
								/>
							
								<FormControlLabel 
									id="kmpf--add-dessert--dessert"
								  	control={<Radio />} 
								  	label="1 Dessert" 
								  	value="dessert"
								  	checked={props.values.kmpFieldAddDessert === "dessert"}
								  	className={ props.values.kmpFieldAddDessert === "dessert" ? 'isChecked' : '' }
								 	onChange={ async (event) => {		

										props.setFieldValue("kmpFieldAddDessert", "dessert");

								 		props.setFieldTouched('kmpFieldAddDessert', true, false);

									}}
								/>

								<FormControlLabel 
									id="kmpf--add-dessert--no"
								  	control={<Radio />} 
								  	label="Neither" 
								  	value="no"
								  	checked={props.values.kmpFieldAddDessert === "no"}
								  	className={ props.values.kmpFieldAddDessert === "no" ? 'isChecked' : '' }
								 	onChange={ async (event) => {		

										props.setFieldValue("kmpFieldAddDessert", "no");

								 		props.setFieldTouched('kmpFieldAddDessert', true, false);

									}}
								/>										

						  	</RadioGroup>

						</FormControl>  
				 
					</Box>	

				) 
				: null 
			}
			   	  
				{/**
			   	   * Radio Group 4 - Do you have any dietary preferences?
			   	   * ------------------------------------------------------------------
			   	   */}

		  	<Box
					className="fieldWrapBox fieldWrapBox--radio optionsNum--4" 
		  		sx={{pb:2}}
		  	>

				<FormControl component="fieldset">

			  		<FormLabel 
			  			component="legend" 
			  			required
			  		>
			  			Do you have any dietary preferences? <span class="required-asterisk">*</span>
						<p id="kmp-errorwrap--diet-type-1"><ErrorMessage name="kmpFieldDietType1" /></p>	
			  		</FormLabel>		  		

				  	<RadioGroup 
						row aria-label="diet-type-1" 
						name="kmpFieldDietType1"
				   	>

						<FormControlLabel 
							id="kmpf--diet-type-1--vegetarian"
						  	control={<Radio />} 
						  	label="Vegetarian" 
						  	value="vegetarian"
						  	checked={props.values.kmpFieldDietType1 === "vegetarian"}
						  	className={ props.values.kmpFieldDietType1 === "vegetarian" ? 'isChecked' : '' }
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldDietType1", "vegetarian");

						 		props.setFieldTouched('kmpFieldDietType1', true, false);

							}}
						/>
					
						<FormControlLabel 
							id="kmpf--diet-type-1--vegan"
						  	control={<Radio />} 
						  	label="Vegan" 
						  	value="vegan"
						  	checked={props.values.kmpFieldDietType1 === "vegan"}
						  	className={ props.values.kmpFieldDietType1 === "vegan" ? 'isChecked' : '' }
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldDietType1", "vegan");

						 		props.setFieldTouched('kmpFieldDietType1', true, false);

							}}
						/>

						<FormControlLabel 
							id="kmpf--diet-type-1--carnivore"
						  	control={<Radio />} 
						  	label="Carnivore" 
						  	value="carnivore"
						  	checked={props.values.kmpFieldDietType1 === "carnivore"}
						  	className={ props.values.kmpFieldDietType1 === "carnivore" ? 'isChecked' : '' }
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldDietType1", "carnivore");

						 		props.setFieldTouched('kmpFieldDietType1', true, false);

							}}          
						/>	

						<FormControlLabel 
							id="kmpf--diet-type-1--no"
						  	control={<Radio />} 
						  	label="None" 
						  	value="no"
						  	checked={props.values.kmpFieldDietType1 === "no"}
						  	className={ props.values.kmpFieldDietType1 === "no" ? 'isChecked' : '' }
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldDietType1", "no");

						 		props.setFieldTouched('kmpFieldDietType1', true, false);

							}}          
						/>							

				  	</RadioGroup>

				</FormControl>  
		 
			</Box>		

				{/**
			   	   * Radio Group 5 - If you don’t have any dietary preferences, would 
			   	   * 				 you like any of the following included in with
			   	   * 				 your standard meal plan?
			   	   * ------------------------------------------------------------------
			   	   */}

			{ 'no' === props.values.kmpFieldDietType1 
				? (
			   	  
				  	<Box
	  					className="fieldWrapBox fieldWrapBox--radio  optionsNum--3" 
				  		sx={{pb:2}}
				  	>

						<FormControl component="fieldset">

					  		<FormLabel 
					  			component="legend" 
					  			required
					  		>
					  			If you don’t have any dietary preferences, would you like any of the following included in with your standard meal plan?
								<p id="kmp-errorwrap--diet-type-2"><ErrorMessage name="kmpFieldDietType2" /></p>	
					  		</FormLabel>													  		
						  	<RadioGroup 
								row aria-label="diet-type-2" 
								name="kmpFieldDietType2"
						   	>
							
								<FormControlLabel 
									id="kmpf--diet-type-2--vegan"
								  	control={<Radio />} 
								  	label="Vegan" 
								  	value="vegan"
								  	checked={props.values.kmpFieldDietType2 === "vegan"}
								  	className={ props.values.kmpFieldDietType2 === "vegan" ? 'isChecked' : '' }
								 	onChange={ async (event) => {		

										props.setFieldValue("kmpFieldDietType2", "vegan");

								 		props.setFieldTouched('kmpFieldDietType2', true, false);

									}}
								/>

								<FormControlLabel 
									id="kmpf--diet-type-2--carnivore"
								  	control={<Radio />} 
								  	label="Carnivore" 
								  	value="carnivore"
								  	checked={props.values.kmpFieldDietType2 === "carnivore"}
								  	className={ props.values.kmpFieldDietType2 === "carnivore" ? 'isChecked' : '' }
								 	onChange={ async (event) => {		

										props.setFieldValue("kmpFieldDietType2", "carnivore");

								 		props.setFieldTouched('kmpFieldDietType2', true, false);

									}}          
								/>	

								<FormControlLabel 
									id="kmpf--diet-type-2--no"
								  	control={<Radio />} 
								  	label="None" 
								  	value="no"
								  	checked={props.values.kmpFieldDietType2 === "no"}
								  	className={ props.values.kmpFieldDietType2 === "no" ? 'isChecked' : '' }
								 	onChange={ async (event) => {		

										props.setFieldValue("kmpFieldDietType2", "no");

								 		props.setFieldTouched('kmpFieldDietType2', true, false);

									}}          
								/>		

						  	</RadioGroup>

						</FormControl>  
				 
					</Box>												  			   	  
			   	 	  ) 
				: null 
			}

				{/**
			   	   * Radio Group 6 - Do you have any food sensitivities or allergies?
			   	   * ------------------------------------------------------------------
			   	   */}
			   	  
		  	<Box 
					className="fieldWrapBox fieldWrapBox--radio  optionsNum--4" 
		  		sx={{pb:2}}
		  	>

				<FormControl component="fieldset">

			  		<FormLabel 
			  			component="legend" 
			  			required
			  		>
			  			Do you have any food sensitivities or allergies? <span class="required-asterisk">*</span>
						<p id="kmp-errorwrap--sensitivities"><ErrorMessage name="kmpFieldSensitivities" /></p>	
			  		</FormLabel>							

				  	<RadioGroup 
						row aria-label="sensitivities" 
						name="kmpFieldSensitivities"
				   	>

						<FormControlLabel 
							id="kmpf--sensitivities--dairy-free"
						  	control={<Radio />} 
						  	label="Dairy-free" 
						  	value="dairy-free"
						  	checked={props.values.kmpFieldSensitivities === "dairy-free"}
						  	className={ props.values.kmpFieldSensitivities === "dairy-free" ? 'isChecked' : '' }
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldSensitivities", "dairy-free");

						 		props.setFieldTouched('kmpFieldSensitivities', true, false);

							}}          
						/>
					
						<FormControlLabel 
							id="kmpf--sensitivities--egg-free"
						  	control={<Radio />} 
						  	label="Egg-free" 
						  	value="egg-free"
						  	checked={props.values.kmpFieldSensitivities === "egg-free"}
						  	className={ props.values.kmpFieldSensitivities === "egg-free" ? 'isChecked' : '' }
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldSensitivities", "egg-free");

						 		props.setFieldTouched('kmpFieldSensitivities', true, false);

							}}
						/>

						<FormControlLabel 
							id="kmpf--sensitivities--other"
						  	control={<Radio />} 
						  	label="Other" 
						  	value="other"
						  	checked={props.values.kmpFieldSensitivities === "other"}
						  	className={ props.values.kmpFieldSensitivities === "other" ? 'isChecked' : '' }
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldSensitivities", "other");

						 		props.setFieldTouched('kmpFieldSensitivities', true, false);

							}}
						/>

						<FormControlLabel 
							id="kmpf--sensitivities--no"
						  	control={<Radio />} 
						  	label="None" 
						  	value="no"
						  	checked={props.values.kmpFieldSensitivities === "no"}
						  	className={ props.values.kmpFieldSensitivities === "no" ? 'isChecked' : '' }
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldSensitivities", "no");

						 		props.setFieldTouched('kmpFieldSensitivities', true, false);

							}}
						/>						

				  	</RadioGroup>

				</FormControl>  
		 
			</Box>	

			{ 'other' === props.values.kmpFieldSensitivities 
				? (

	  				/**
	  			   	   * Conditional Textarea - Other Sensitivities
	  			   	   * ------------------------------------------------------------------
	  			   	   */
	  			   	  
				  	<Box 
	  					className="fieldWrapBox fieldWrapBox--textarea fieldWrapBox--sensitivitiesOther"
				  		sx={{pb:2}}
				  	>

						<Field 
						  	name="kmpFieldSensitivitiesOther" 
						  	component={TextField} 
						  	label="What other food sensitivities do you have?" 
						  	inputProps={{ onChange:props.handleChange }}
						  	value={props.values.kmpFieldSensitivitiesOther}
						 	placeholder=""
						 	rows="5"
						 	multiline
						 	fullWidth
						 	/*In case of the optional textarea, we only need an onClick check/callback. Why? Because
						 	1. Browser autofills don't work with textareas - so we don't need an onChange autofill-fix.
						 	2. The field is optional - but, for the sake of safety, we need the touched value to be correct.
						 	3. If there's an onClick, we can be sure there will be an onBlur. But onClick is more secure in this case, by using onClick there's no need to check if a onBlur works as expected even with a field-blur-by-form-submit user action.*/
							onBlur={ async (event) => {
								props.setFieldTouched('kmpFieldSensitivitiesOther', true, true);
							}}											 	
						/>								
				  
				  	</Box>											

				)
				: null

			}

				{/**
			   	   * Radio Group 7 - Do you prefer beginner-friendly, simple meals or
			   	   * 				 more complex recipes?
			   	   * ------------------------------------------------------------------
			   	   */}
			   	  
		  	<Box
					className="fieldWrapBox fieldWrapBox--radio optionsNum--3" 
		  		sx={{pb:2}}
		  	>

				<FormControl component="fieldset">

			  		<FormLabel 
			  			component="legend" 
			  			required
			  		>
			  			Do you prefer beginner-friendly, simple meals or more complex recipes? <span class="required-asterisk">*</span>
						<p id="kmp-errorwrap--meal-complexity"><ErrorMessage name="kmpFieldMealComplexity" /></p>	
			  		</FormLabel>	

				  	<RadioGroup 
						row aria-label="meal-complexity" 
						name="kmpFieldMealComplexity"
				   	>

						<FormControlLabel 
							id="kmpf--meal-complexity--beginner"
						  	control={<Radio />} 
						  	label="Beginner-friendly" 
						  	value="beginner"
						  	checked={props.values.kmpFieldMealComplexity === "beginner"}
						  	className={ props.values.kmpFieldMealComplexity === "beginner" ? 'isChecked' : '' }
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldMealComplexity", "beginner");

						 		props.setFieldTouched('kmpFieldMealComplexity', true, false);

							}}
						/>
					
						<FormControlLabel 
							id="kmpf--meal-complexity--advanced"
						  	control={<Radio />} 
						  	label="Advanced Recipes" 
						  	value="advanced"
						  	checked={props.values.kmpFieldMealComplexity === "advanced"}
						  	className={ props.values.kmpFieldMealComplexity === "advanced" ? 'isChecked' : '' }
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldMealComplexity", "advanced");

						 		props.setFieldTouched('kmpFieldMealComplexity', true, false);

							}}
						/>

						<FormControlLabel 
							id="kmpf--meal-complexity--no"
						  	control={<Radio />} 
						  	label="No Preference" 
						  	value="no"
						  	checked={props.values.kmpFieldMealComplexity === "no"}
						  	className={ props.values.kmpFieldMealComplexity === "no" ? 'isChecked' : '' }
						 	onChange={ async (event) => {		

								props.setFieldValue("kmpFieldMealComplexity", "no");

						 		props.setFieldTouched('kmpFieldMealComplexity', true, false);

							}}
						/>										

				  	</RadioGroup>

				</FormControl>  
		 
			</Box>
			   	  
				{/**
			   	   * Textarea, NOT REQUIRED - What are your goals?
			   	   * ------------------------------------------------------------------
			   	   */}		  
			   	  
		  	<Box
				className="fieldWrapBox fieldWrapBox--textarea fieldWrapBox--radioLabelMargin" 
		  		sx={{pb:2}}
		  	>

				<Typography id="kmp-label--your-goals" gutterBottom>What are your goals?</Typography>

				<Field 
				  	name="kmpFieldYourGoals" 
				  	component={TextField} 
				  	label="What are your goals?" 
				  	inputProps={{ onChange:props.handleChange }}
				  	value={props.values.kmpFieldYourGoals}
				 	placeholder=""
				 	rows="5"
				 	multiline
				 	fullWidth
				 	/*In case of the optional textarea, we only need an onClick check/callback. Why? Because
				 	1. Browser autofills don't work with textareas - so we don't need an onChange autofill-fix.
				 	2. The field is optional - but, for the sake of safety, we need the touched value to be correct.
				 	3. If there's an onClick, we can be sure there will be an onBlur. But onClick is more secure in this case, by using onClick there's no need to check if a onBlur works as expected even with a field-blur-by-form-submit user action.*/
					onBlur={ async (event) => {
						props.setFieldTouched('kmpFieldYourGoals', true, true);
					}}											 	
				/>								
		  
		  	</Box> 	 

		  	<Box
				className="fieldWrapBox fieldWrapBox--checkbox fieldWrapBox--agreementBox" 
		  		sx={{pb:2}}
		  	>

				<Box
					className={'checkboxWithLabelWrap'}
					sx={{
						display: 'flex',
						flexWrap: 'nowrap',
						justifyContent: 'flex-start',
						alignItems: 'stretch',
						alignContent: 'flex-start',
					}}
				>

					<Box
						className={'checkboxWrap'}
						sx={{
							flexGrow: 0,
						}}
					>

						<Field  
						  	type="checkbox"
						  	name="kmpFieldAgreement"
						  	id="kmpFieldAgreement" 
						  	component={Checkbox}
						  	value={props.values.kmpFieldAgreement}
						  	inputProps={{ onChange:props.handleChange }}
						  	checked={props.values.kmpFieldAgreement === true ? true : false}      
							onChange={ async (event) => {		
								props.setFieldTouched('kmpFieldAgreement', true, false);
							}}	
							onClick={ async (event) => {		
								props.setFieldTouched('kmpFieldAgreement', true, false);
							}}			  	          
						/>

					</Box>

					<Box
						sx={{
							flexGrow: 1,
							mt: .25
						}}
					>

						<label htmlFor="kmpFieldAgreement">I agree to the Ketogevity <a href="https://ketogenic.com/terms/" rel="noreferrer" target="_blank">Terms & Conditions<i className="far fa-external-link" aria-hidden="true"></i></a>
						</label>

						<p id="kmp-errorwrap--agreement"><ErrorMessage name="kmpFieldAgreement" /></p>

					</Box>

				</Box>

			</Box>			  			  										

		  	{/*<Typography 
		  		sx={{ mt: 2, mb: 1 }}
		  	>
		  		Step {activeStep + 1}
		  	</Typography>*/}

		  	<Box 
				className="buttonWrapBox" 
		  		sx={{ pt: 2, color: 'white' }}
		  	>

				{ ( 
					props.errors.kmpFieldFasting 			||
					props.errors.kmpFieldMealsNumber 		||
					props.errors.kmpFieldAddDessert 		||
					props.errors.kmpFieldDietType1 			||
					props.errors.kmpFieldDietType2 			||
					props.errors.kmpFieldSensitivities  	||
					props.errors.kmpFieldMealComplexity					
				  )
					? (					

						<p className="required-reminder">please answer all required questions.</p>

					) : null
				} 		  	
		    										  	
			  	<Button
					startIcon={props.isSubmitting ? <CircularProgress size="1.25rem" thickness="8" color="inherit" /> : null} 
					disabled={ (
									( 	
										false === isDevMode &&
										( 
											props.values.kmpFieldAgreement === false ||
											!props.touched.kmpFieldAgreement 
										)
									) ||
									// if 
									// 	- the cookie solution is active AND 
									// 	- the user HAS NOT the required membership level AND
									// 	- the kmp-ck-plannerUsed cookie value equals to the 
									// 	  restrictNum value
									(
										( true === withCookies && false === isPlatinumMember ) &&
										( restrictNum === parseInt( Cookies.get('kmp-ck-plannerUsed') ) )
									)
								) ? true 
								  : props.isSubmitting }
					variant="contained"
					color="primary"
					className={(
									( 	
										false === isDevMode &&
										( 
											props.values.kmpFieldAgreement === false ||
											!props.touched.kmpFieldAgreement 
										)
									) ||
									// if 
									// 	- the cookie solution is active AND 
									// 	- the user HAS NOT the required membership level AND
									// 	- the kmp-ck-plannerUsed cookie value equals to the 
									// 	  restrictNum value
									(
										( true === withCookies && false === isPlatinumMember ) &&
										( restrictNum === parseInt( Cookies.get('kmp-ck-plannerUsed') ) )
									)									
								) ? 'nextButton button--disabled' 
								  : 'nextButton button--enabled'}
					onClick={ async (event) => {

						/*Set up constants to be forwarded to the handleNext function.*/
						const requiredFieldsTouched = props.touched.kmpFieldAgreement
								? true
								: false;

						/*If the user didn't touch some of the fields, set all step field touched value to true - this way we can trigger field-level validation upon button click; the handleNext function already has the parameters necessary to "decide" if the form step can be upgraded or further user action is reqired before the pagination.*/
						if ( false === requiredFieldsTouched ) {

							props.setFieldTouched(
								'kmpFieldAgreement', 	// field 
								true,  					// is touched?
								true 					// should validate?
							);

						}

						console.log(
							( true === withCookies && false === isPlatinumMember ) &&
							( restrictNum === parseInt( Cookies.get('kmp-ck-plannerUsed') ) )
						)

						console.log( 	
							false === isDevMode &&
							( 
								props.values.kmpFieldAgreement === false ||
								!props.touched.kmpFieldAgreement 
							)
						)				

						console.log( withCookies );
						console.log( restrictNum );
						console.log( parseInt( Cookies.get('kmp-ck-plannerUsed') ) );
						console.log( restrictNum === parseInt( Cookies.get('kmp-ck-plannerUsed') ) );
						console.log( ckExpMins );

						// only run the submit callback if
						//  - the cookie solution is not active OR 
						//  - the cookie solution is active AND the user HAS the required
						//    membership OR
						//  - ( 
						//     - the cookie solution is active AND
						// 	   - the user HAS NOT the required membership level AND
						// 	   - the kmp-ck-plannerUsed cookie value is less than the 
						// 	     restrictNum value 
						// 	   )
						if (
								( false === withCookies ) ||
								( true === withCookies && true === isPlatinumMember ) ||
								(
									( true === withCookies && false === isPlatinumMember ) &&
									( restrictNum > parseInt( Cookies.get('kmp-ck-plannerUsed') ) )
								)
							)
							props.handleSubmit();

					}}	
			  	>
					Generate My Custom Meal Plan
			  	</Button>

				{ (
						// if 
						// 	- the cookie solution is active AND 
						// 	- the user HAS NOT the required membership level AND
						// 	- the kmp-ck-plannerUsed cookie value equals to the 
						// 	  restrictNum value 
						(
							( true === withCookies && false === isPlatinumMember ) &&
							( restrictNum === parseInt( Cookies.get('kmp-ck-plannerUsed') ) )
						)
				   )
					? (	

						<React.Fragment>

							<p id="kmp-errorwrap--restricted">Unlimited form submissions is a feature for members only. <a href="/join/" target="_blank">JOIN TODAY!</a></p>

						</React.Fragment>

		  			) : null
				}

		  	</Box>	

		  	</CardContent>
		</Card>

		</React.Fragment>

	  ) : null

}

{/**
   * EOF STEPS
   * ==============================================================================================
   */}		
		
							{ isDevMode
								? (

									<DisplayFormikState {...props} />	

								)
								: null
							}

							{/*<DisplayFormikState {...props} />*/}

	         			</Form>
	       
	       			)}
	     
	     		</Formik>				
		    	
		</Box>

		</ThemeProvider>		
  	
  	);

}