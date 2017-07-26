	<div id="footer" class="row">
		<div class="wrap">
			<div class="col-xs-12 col-md-3">
				<p id="copyright">
					&copy; <a href="" target="_blank">Prince Seating Corp</a> All Right Reserverd
				</p>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="widget">
					<h2 class="title"><?php _e('Pages', 'ps'); ?></h2>
					<div class="body">
						<?php 
						lt_show_content_menu('footer-pages_'.LANGUAGE, array(
														'class' => 'menu vertical', 
														'sub_menu_class' => 'submenu'))
						?>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-3"></div>
			<div class="col-xs-12 col-md-3">
				<div class="widget">
					<h2 class="title"><?php _e('Join Our Mailing List'); ?></h2>
					<div class="body">
						<form id="ema_signup_form" target="_blank" action="https://madmimi.com/signups/subscribe/361556" 
							accept-charset="UTF-8" method="post">
							<input name="utf8" type="hidden" value="✓"/>
							<div class="form-group mimi_field">
								<label for="signup_name">Name</label>
								<input id="signup_name" name="signup[name]" type="text" class="form-control" 
									data-required-field="This field is required"/>
							</div>
							<div class="form-group mimi_field required">
								<label for="signup_email">Email*</label>
								<input id="signup_email" name="signup[email]" type="text" class="form-control" 
									data-required-field="This field is required" placeholder="you@example.com"/>
							</div>
							<div class="form-group mimi_field">
							  <input type="submit" class="submit btn btn-default" value="Subscribe" id="webform_submit_button" data-default-text="Subscribe" data-submitting-text="Sending..." data-invalid-text="↑ You forgot some required fields" data-choose-list="↑ Choose a list" data-thanks="Thank you!"/>
							</div>
						</form>
					</div><!-- end class="body" -->
				</div>
				<script type="text/javascript">
				(function(global) {
				  function serialize(form){if(!form||form.nodeName!=="FORM"){return }var i,j,q=[];for(i=form.elements.length-1;i>=0;i=i-1){if(form.elements[i].name===""){continue}switch(form.elements[i].nodeName){case"INPUT":switch(form.elements[i].type){case"text":case"hidden":case"password":case"button":case"reset":case"submit":q.push(form.elements[i].name+"="+encodeURIComponent(form.elements[i].value));break;case"checkbox":case"radio":if(form.elements[i].checked){q.push(form.elements[i].name+"="+encodeURIComponent(form.elements[i].value))}break;case"file":break}break;case"TEXTAREA":q.push(form.elements[i].name+"="+encodeURIComponent(form.elements[i].value));break;case"SELECT":switch(form.elements[i].type){case"select-one":q.push(form.elements[i].name+"="+encodeURIComponent(form.elements[i].value));break;case"select-multiple":for(j=form.elements[i].options.length-1;j>=0;j=j-1){if(form.elements[i].options[j].selected){q.push(form.elements[i].name+"="+encodeURIComponent(form.elements[i].options[j].value))}}break}break;case"BUTTON":switch(form.elements[i].type){case"reset":case"submit":case"button":q.push(form.elements[i].name+"="+encodeURIComponent(form.elements[i].value));break}break}}return q.join("&")};


				  function extend(destination, source) {
					for (var prop in source) {
					  destination[prop] = source[prop];
					}
				  }

				  if (!Mimi) var Mimi = {};
				  if (!Mimi.Signups) Mimi.Signups = {};

				  Mimi.Signups.EmbedValidation = function() {
					this.initialize();

					var _this = this;
					if (document.addEventListener) {
					  this.form.addEventListener('submit', function(e){
						_this.onFormSubmit(e);
					  });
					} else {
					  this.form.attachEvent('onsubmit', function(e){
						_this.onFormSubmit(e);
					  });
					}
				  };

				  extend(Mimi.Signups.EmbedValidation.prototype, {
					initialize: function() {
					  this.form         = document.getElementById('ema_signup_form');
					  this.submit       = document.getElementById('webform_submit_button');
					  this.callbackName = 'jsonp_callback_' + Math.round(100000 * Math.random());
					  this.validEmail   = /.+@.+\..+/
					},

					onFormSubmit: function(e) {
					  e.preventDefault();

					  this.validate();
					  if (this.isValid) {
						this.submitForm();
					  } else {
						this.revalidateOnChange();
					  }
					},

					validate: function() {
					  this.isValid = true;
					  this.emailValidation();
					  this.fieldAndListValidation();
					  this.updateFormAfterValidation();
					},

					emailValidation: function() {
					  var email = document.getElementById('signup_email');

					  if (this.validEmail.test(email.value)) {
						this.removeTextFieldError(email);
					  } else {
						this.textFieldError(email);
						this.isValid = false;
					  }
					},

					fieldAndListValidation: function() {
					  var fields = this.form.querySelectorAll('.mimi_field.required');

					  for (var i = 0; i < fields.length; ++i) {
						var field = fields[i],
							type  = this.fieldType(field);
						if (type === 'checkboxes' || type === 'radio_buttons') {
						  this.checkboxAndRadioValidation(field);
						} else {
						  this.textAndDropdownValidation(field, type);
						}
					  }
					},

					fieldType: function(field) {
					  var type = field.querySelectorAll('.field_type');

					  if (type.length) {
						return type[0].getAttribute('data-field-type');
					  } else if (field.className.indexOf('checkgroup') >= 0) {
						return 'checkboxes';
					  } else {
						return 'text_field';
					  }
					},

					checkboxAndRadioValidation: function(field) {
					  var inputs   = field.getElementsByTagName('input'),
						  selected = false;

					  for (var i = 0; i < inputs.length; ++i) {
						var input = inputs[i];
						if((input.type === 'checkbox' || input.type === 'radio') && input.checked) {
						  selected = true;
						}
					  }

					  if (selected) {
						field.className = field.className.replace(/ invalid/g, '');
					  } else {
						if (field.className.indexOf('invalid') === -1) {
						  field.className += ' invalid';
						}

						this.isValid = false;
					  }
					},

					textAndDropdownValidation: function(field, type) {
					  var inputs = field.getElementsByTagName('input');

					  for (var i = 0; i < inputs.length; ++i) {
						var input = inputs[i];
						if (input.name.indexOf('signup') >= 0) {
						  if (type === 'text_field') {
							this.textValidation(input);
						  } else {
							this.dropdownValidation(field, input);
						  }
						}
					  }
					  this.htmlEmbedDropdownValidation(field);
					},

					textValidation: function(input) {
					  if (input.id === 'signup_email') return;

					  if (input.value) {
						this.removeTextFieldError(input);
					  } else {
						this.textFieldError(input);
						this.isValid = false;
					  }
					},

					dropdownValidation: function(field, input) {
					  if (input.value) {
						field.className = field.className.replace(/ invalid/g, '');
					  } else {
						if (field.className.indexOf('invalid') === -1) field.className += ' invalid';
						this.onSelectCallback(input);
						this.isValid = false;
					  }
					},

					htmlEmbedDropdownValidation: function(field) {
					  var dropdowns = field.querySelectorAll('.mimi_html_dropdown');
					  var _this = this;

					  for (var i = 0; i < dropdowns.length; ++i) {
						var dropdown = dropdowns[i];

						if (dropdown.value) {
						  field.className = field.className.replace(/ invalid/g, '');
						} else {
						  if (field.className.indexOf('invalid') === -1) field.className += ' invalid';
						  this.isValid = false;
						  dropdown.onchange = (function(){ _this.validate(); });
						}
					  }
					},

					textFieldError: function(input) {
					  input.className   = 'required invalid';
					  input.placeholder = input.getAttribute('data-required-field');
					},

					removeTextFieldError: function(input) {
					  input.className   = 'required';
					  input.placeholder = '';
					},

					onSelectCallback: function(input) {
					  if (typeof Widget === 'undefined' || !Widget.BasicDropdown) return;

					  var dropdownEl = input.parentNode,
						  instances  = Widget.BasicDropdown.instances,
						  _this = this;

					  for (var i = 0; i < instances.length; ++i) {
						var instance = instances[i];
						if (instance.wrapperEl === dropdownEl) {
						  instance.onSelect = function(){ _this.validate() };
						}
					  }
					},

					updateFormAfterValidation: function() {
					  this.form.className   = this.setFormClassName();
					  this.submit.value     = this.submitButtonText();
					  this.submit.disabled  = !this.isValid;
					  this.submit.className = this.isValid ? 'submit' : 'disabled';
					},

					setFormClassName: function() {
					  var name = this.form.className;

					  if (this.isValid) {
						return name.replace(/\s?mimi_invalid/, '');
					  } else {
						if (name.indexOf('mimi_invalid') === -1) {
						  return name += ' mimi_invalid';
						} else {
						  return name;
						}
					  }
					},

					submitButtonText: function() {
					  var invalidFields = document.querySelectorAll('.invalid'),
						  text;

					  if (this.isValid || !invalidFields) {
						text = this.submit.getAttribute('data-default-text');
					  } else {
						if (invalidFields.length || invalidFields[0].className.indexOf('checkgroup') === -1) {
						  text = this.submit.getAttribute('data-invalid-text');
						} else {
						  text = this.submit.getAttribute('data-choose-list');
						}
					  }
					  return text;
					},

					submitForm: function() {
					  this.formSubmitting();

					  var _this = this;
					  window[this.callbackName] = function(response) {
						delete window[this.callbackName];
						document.body.removeChild(script);
						_this.onSubmitCallback(response);
					  };

					  var script = document.createElement('script');
					  script.src = this.formUrl('json');
					  document.body.appendChild(script);
					},

					formUrl: function(format) {
					  var action  = this.form.action;
					  if (format === 'json') action += '.json';
					  return action + '?callback=' + this.callbackName + '&' + serialize(this.form);
					},

					formSubmitting: function() {
					  this.form.className  += ' mimi_submitting';
					  this.submit.value     = this.submit.getAttribute('data-submitting-text');
					  this.submit.disabled  = true;
					  this.submit.className = 'disabled';
					},

					onSubmitCallback: function(response) {
					  if (response.success) {
						this.onSubmitSuccess(response.result);
					  } else {
						top.location.href = this.formUrl('html');
					  }
					},

					onSubmitSuccess: function(result) {
					  if (result.has_redirect) {
						top.location.href = result.redirect;
					  } else if(result.single_opt_in || !result.confirmation_html) {
						this.disableForm();
						this.updateSubmitButtonText(this.submit.getAttribute('data-thanks'));
					  } else {
						this.showConfirmationText(result.confirmation_html);
					  }
					},

					showConfirmationText: function(html) {
					  var fields = this.form.querySelectorAll('.mimi_field');

					  for (var i = 0; i < fields.length; ++i) {
						fields[i].style['display'] = 'none';
					  }

					  (this.form.querySelectorAll('fieldset')[0] || this.form).innerHTML = html;
					},

					disableForm: function() {
					  var elements = this.form.elements;
					  for (var i = 0; i < elements.length; ++i) {
						elements[i].disabled = true;
					  }
					},

					updateSubmitButtonText: function(text) {
					  this.submit.value = text;
					},

					revalidateOnChange: function() {
					  var fields = this.form.querySelectorAll(".mimi_field.required"),
						  _this = this;

					  for (var i = 0; i < fields.length; ++i) {
						var inputs = fields[i].getElementsByTagName('input');
						for (var j = 0; j < inputs.length; ++j) {
						  if (this.fieldType(fields[i]) === 'text_field') {
							inputs[j].onkeyup = function() {
							  var input = this;
							  if (input.getAttribute('name') === 'signup[email]') {
								if (_this.validEmail.test(input.value)) _this.validate();
							  } else {
								if (input.value.length === 1) _this.validate();
							  }
							}
						  } else {
							inputs[j].onchange = function(){ _this.validate() };
						  }
						}
					  }
					}
				  });

				  if (document.addEventListener) {
					document.addEventListener("DOMContentLoaded", function() {
					  new Mimi.Signups.EmbedValidation();
					});
				  }
				  else {
					window.attachEvent('onload', function() {
					  new Mimi.Signups.EmbedValidation();
					});
				  }
				})(this);
				</script>
			</div>
		</div>
	</div>
</div>
<?php lt_footer(); ?>
</body>
</html>