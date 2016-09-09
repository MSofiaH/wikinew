<?php
// @codingStandardsIgnoreFile
/**
 * Html form for account creation (since 1.22 with VForm appearance).
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Templates
 */

class UsercreateTemplate extends BaseTemplate {
	/**
	 * Extensions (AntiSpoof and TitleBlacklist) call this in response to
	 * UserCreateForm hook to add checkboxes to the create account form.
	 */
	function addInputItem( $name, $value, $type, $msg, $helptext = false ) {
		$this->data['extraInput'][] = array(
			'name' => $name,
			'value' => $value,
			'type' => $type,
			'msg' => $msg,
			'helptext' => $helptext,
		);
	}

	function execute() {
?>
<div class="mw-ui-container">
	<?php if ( $this->haveData( 'languages' ) ) { ?>
		<div id="languagelinks">
			<p><?php $this->html( 'languages' ); ?></p>
		</div>
	<?php }
	      if ( !wfMessage( 'signupstart' )->isDisabled() ) { ?>
		<div id="signupstart"><?php $this->msgWiki( 'signupstart' ); ?></div>
	<?php } ?>
	<div id="userloginForm">
		<form name="userlogin2" id="userlogin2" class="mw-ui-vform" method="post" action="<?php $this->text( 'action' ); ?>">





			<!-- This element is used by the mediawiki.special.userlogin.signup.js module. -->
			<div
				id="mw-createacct-status-area"
				<?php if ( $this->data['message'] ) { ?>
					class="<?php echo $this->data['messagetype']; ?>box"
				<?php } else { ?>
					style="display: none;"
				<?php } ?>
			>
			<?php if ( $this->data['message'] ) { ?>
					<?php if ( $this->data['messagetype'] == 'error' ) { ?>
						<strong><?php $this->msg( 'createacct-error' ); ?></strong>
						<br />
					<?php } ?>
					<?php $this->html( 'message' ); ?>
			<?php } ?>
			</div>

                        <?php if ( $this->data['formheader'] ) { ?>
                                <div class="mw-form-formheader">
                                        <?php $this->html( 'formheader' ); /* extensions such as MobileFrontend add html here */ ?>
                                </div>
                        <?php } ?>

			<div class="mw-ui-vform-field">
				<label for='wpName2'>
					<?php $this->msg( 'userlogin-yourname' ); ?>

					<span class="mw-ui-flush-right"><?php echo $this->getMsg( 'createacct-helpusername' )->parse(); ?></span>
				</label>
				<?php
				echo Html::input( 'wpName', $this->data['name'], 'text', array(
					'class' => 'mw-ui-input loginText',
					'id' => 'wpName2',
					'tabindex' => '1',
					'size' => '20',
					'required',
					'placeholder' => $this->getMsg( $this->data['loggedin'] ?
						'createacct-another-username-ph' : 'userlogin-yourname-ph' )->text(),
				) );
				?>
			</div>

			<div class="mw-ui-vform-field">
				<?php if ( $this->data['createemail'] ) { ?>
					<div class="mw-ui-checkbox">
						<input name="wpCreateaccountMail" type="checkbox" value="1" id="wpCreateaccountMail" tabindex="2"
							<?php if ( $this->data['createemailset'] ) {
								echo 'checked="checked"';
							} ?>
						><label for="wpCreateaccountMail">
							<?php $this->msg( 'createaccountmail' ); ?>
						</label>
					</div>
				<?php } ?>
			</div>

			<div class="mw-ui-vform-field mw-row-password">
				<label for='wpPassword2'><?php $this->msg( 'userlogin-yourpassword' ); ?></label>
				<?php
				echo Html::input( 'wpPassword', null, 'password', array(
					'class' => 'mw-ui-input loginPassword',
					'id' => 'wpPassword2',
					'tabindex' => '3',
					'size' => '20',
					'required',
					'placeholder' => $this->getMsg( 'createacct-yourpassword-ph' )->text()
				) + User::passwordChangeInputAttribs() );
				?>
			</div>

			<?php
			if ( $this->data['usedomain'] ) {
				$select = new XmlSelect( 'wpDomain', false, $this->data['domain'] );
				$select->setAttribute( 'tabindex', 4 );
				foreach ( $this->data['domainnames'] as $dom ) {
					$select->addOption( $dom );
				}
			?>
				<div class="mw-ui-vform-field" id="mw-user-domain-section">
					<label for="wpDomain"><?php $this->msg( 'yourdomainname' ); ?></label>
					<div>
						<?php echo $select->getHTML(); ?>
					</div>
				</div>
			<?php } ?>

			<div class="mw-ui-vform-field mw-row-password">
				<label for='wpRetype'><?php $this->msg( 'createacct-yourpasswordagain' ); ?></label>
				<?php
				echo Html::input( 'wpRetype', null, 'password', array(
					'class' => 'mw-ui-input loginPassword',
					'id' => 'wpRetype',
					'tabindex' => '5',
					'size' => '20',
					'required',
					'placeholder' => $this->getMsg( 'createacct-yourpasswordagain-ph' )->text()
					) + User::passwordChangeInputAttribs() );
				?>
			</div>

			<div class="mw-ui-vform-field">
				<?php if ( $this->data['useemail'] ) { ?>
					<label for='wpEmail'>
						<?php
							$this->msg( 'createacct-emailrequired' );
						?>
					</label>
					<?php
						echo Html::input( 'wpEmail', $this->data['email'], 'email', array(
							'class' => 'mw-ui-input loginText',
							'id' => 'wpEmail',
							'tabindex' => '6',
							'size' => '20',
							'required' => 'true',
							'placeholder' => $this->getMsg( $this->data['loggedin'] ?
								'createacct-another-email-ph' : 'createacct-email-ph' )->text()
						) );
					?>
				<?php } ?>
			</div>

			<?php if ( $this->data['userealname'] ) { ?>
				<div class="mw-ui-vform-field">
					<label for='wpRealName'>Nombre real</label>
					<input type='text' class='mw-ui-input loginText' name="wpRealName" id="wpRealName"
						tabindex="7" placeholder = 'Escribe tu Nombre y Apellido'
						value="<?php $this->text( 'realname' ); ?>" size='20' required = 'true'/>

				</div>
			<?php } ?>

			<div class="mw-ui-vform-field">
				<label for='wpCuenta'>Cuenta</label>
				<input type='text' class='mw-ui-input loginText' name="wpCuenta" id="wpCuenta"
					   tabindex="8" placeholder = 'Escribe tu numero de cuenta'
					   value="<?php $this->text( 'cuenta' ); ?>" size='20' required = 'true'
					   onkeypress="return isNumberKey(event)"/>
				<SCRIPT type="text/javascript">
					function isNumberKey(evt){
						var charCode = (evt.which) ? evt.which : event.keyCode
						if (charCode > 31 && (charCode < 48 || charCode > 57))
							return false;
						return true;
					}
				</SCRIPT>
			</div>

			<div class="mw-ui-vform-field">
				<label for='wpCampus'>Campus</label>
				<select class='mw-ui-input loginText' name="wpCampus" id="wpCampus"
						tabindex="10" required>
					<option value="" disable selected>Click aquí para seleccionar Campus</option>
					<option value="utgu" >Unitec TGU</option>
					<option value="ctgu" >Ceutec TGU</option>
					<option value="usps" >Unitec SPS</option>
					<option value="csps" >Ceutec SPS</option>
				</select>
			</div>

			<div class="mw-ui-vform-field">
				<label for='wpCarrera' > Carrera</label >
				<select class='mw-ui-input loginText' name = "wpCarrera" id = "wpCarrera"
						tabindex = "9" required >
					<option value = "" disable selected > Click aquí para seleccionar Carrera </option >
					<option value = "Ingeniería en Sistemas Computacionales" > Ingeniería en Sistemas Computacionales </option >
					<option value = "Ingeniería Industrial y de Sistemas" > Ingeniería Industrial y de Sistemas </option >
					<option value = "Inteniería Civil" > Inteniería Civil </option >
					<option value = "Ingeniería Telecomunicaciones" > Ingeniería Telecomunicaciones </option >
					<option value = "Ingeniería Mecatrónica" > Ingeniería Mecatrónica </option >
					<option value = "Ingeniería Informática" > Ingeniería Informática </option >
					<option value = "Ingeniería Energía" > Ingeniería Energía </option >
					<option value = "Ingeniería Biomédica" > Ingeniería Biomédica </option >
					<option value = "Arquitectura" > Arquitectura</option >
					<option value = "Licenciatura Administración Industrial y de Negocios" > Licenciatura Administración Industrial y de Negocios </option >
					<option value = "Licenciatura Administración de Empresas Turísticas" > Licenciatura Administración de Empresas Turísticas </option >
					<option value = "Licenciatura en Comunicación Audiovisual y Publicitaria" > Licenciatura en Comunicación Audiovisual y Publicitaria </option >
					<option value = "Licenciatura en Diseño Gráfico" > Licenciatura en Diseño Gráfico </option >
					<option value = "Licenciatura Derecho" > Licenciatura Derecho </option >
					<option value = "Licenciatura Finanzas" > Licenciatura Finanzas </option >
					<option value = "Licenciatura Mercadotecnica y Negocios Internacionales" > Licenciatura Mercadotecnica y Negocios Internacionales </option >
					<option value = "Licenciatura en Relaciones Internacionales" > Licenciatura en Relaciones Internacionales </option >
					<option value = "Licencuatura en Psicología" > Licencuatura en Psicología </option >
					<option value = "Licenciatura en Gastronomía" > Licenciatura en Gastronomía </option >
					<option value = "Medicina y Cirugía" > Medicina y Cirugía </option >
					<option value = "Cirugía Dental" > Cirugía Dental </option >
					<option value = "Nutrición" > Nutrición</option >
					<option value = "Maestría en Dirección Empresarial" > Maestría en Dirección Empresarial </option >
					<option value = "Maestría en Finanzas" > Maestría en Finanzas </option >
					<option value = "Maestría en Administración de Proyectos" > Maestría en Administración de Proyectos </option >
					<option value = "Maestría en Dirección de Mercadotecnia" > Maestría en Dirección de Mercadotecnia </option >
					<option value = "Maestría en Ingeniería de Estructuras" > Maestría en Ingeniería de Estructuras </option >
					<option value = "Maestría en Gestión de Sistemas de Calidad Integrados" > Maestría en Gestión de Sistemas de Calidad Integrados </option >
					<option value = "Maestría en Gestión de Energías Renovables" > Maestría en Gestión de Energías Renovables </option >
					<option value = "Maestría en Derecho Empresarial" > Maestría en Derecho Empresarial </option >
					<option value = "Maestría en Dirección de Recursos Humanos" > Maestría en Dirección de Recursos Humanos </option >
					<option value = "Maestría en Desarrollo Local y Turismo" > Maestría en Desarrollo Local y Turismo </option >
					<option value = "Maestría en Gestión de Tecnologías de Información" > Maestría en Gestión de Tecnologías de Información </option >
					<option value = "Maestría en Gestión de Operaciones Logísticas" > Maestría en Gestión de Operaciones Logísticas </option >
					<option value = "Inglés" > Departamento de Inglés </option >
					<option value = "Física Matemáticas" > Departamento de Físico Matemático </option >
					<option value = "Español y Humanidades" > Departamento de Español y Humanidades </option >
					<option value = "Otro" > Otro</option >
				</select >
			</div >

			<div class="mw-ui-vform-field">
				<div class="mw-ui-checkbox">
					<input name="wpCatedratico" type="checkbox" value="1" id="wpCatedratico" tabindex="11"
						<?php if ( isset($_POST['wpCatedratico'] )) {
							echo 'checked="checked"';
						} ?>
					><label for="wpCatedratico">
						Eres catedrático de la Universidad?
					</label>
				</div>
			</div>

			<?php if ( $this->data['usereason'] ) { ?>
				<div class="mw-ui-vform-field">
					<label for='wpReason'><?php $this->msg( 'createacct-reason' ); ?></label>
					<?php echo Html::input( 'wpReason', $this->data['reason'], 'text', array(
						'class' => 'mw-ui-input loginText',
						'id' => 'wpReason',
						'tabindex' => '8',
						'size' => '20',
						'placeholder' => $this->getMsg( 'createacct-reason-ph' )->text()
					) ); ?>
				</div>
			<?php } ?>

			<?php
			$tabIndex = 9;
			if ( isset( $this->data['extraInput'] ) && is_array( $this->data['extraInput'] ) ) {
				foreach ( $this->data['extraInput'] as $inputItem ) { ?>
					<div class="mw-ui-vform-field">
						<?php
						// If it's a checkbox, output the whole thing (assume it has a msg).
						if ( $inputItem['type'] == 'checkbox' ) {
						?>
							<div class="mw-ui-checkbox">
								<input
									name="<?php echo htmlspecialchars( $inputItem['name'] ); ?>"
									id="<?php echo htmlspecialchars( $inputItem['name'] ); ?>"
									type="checkbox" value="1"
									tabindex="<?php echo $tabIndex++; ?>"
									<?php if ( !empty( $inputItem['value'] ) ) {
										echo 'checked="checked"';
									} ?>
								><label for="<?php echo htmlspecialchars( $inputItem['name'] ); ?>">
									<?php $this->msg( $inputItem['msg'] ); ?>
								</label>
							</div>
						<?php
						} else {
							// Not a checkbox.
							// TODO (bug 31909) support other input types, e.g. select boxes.
						?>
							<?php if ( !empty( $inputItem['msg'] ) ) { ?>
								<label for="<?php echo htmlspecialchars( $inputItem['name'] ); ?>">
									<?php $this->msgWiki( $inputItem['msg'] ); ?>
								</label>
							<?php } ?>
							<input
								type="<?php echo htmlspecialchars( $inputItem['type'] ); ?>"
								class="mw-ui-input"
								name="<?php echo htmlspecialchars( $inputItem['name'] ); ?>"
								tabindex="<?php echo $tabIndex++; ?>"
								value="<?php echo htmlspecialchars( $inputItem['value'] ); ?>"
								id="<?php echo htmlspecialchars( $inputItem['name'] ); ?>"
							/>
						<?php } ?>
					</div>
				<?php
				}
			}
			// A separate placeholder for any inserting any extrafields, e.g used by ConfirmEdit extension
			if ( $this->haveData( 'extrafields' ) ) {
				echo $this->data['extrafields'];
			}
			// skip one index.
			$tabIndex++;
			?>


                        <section class="mw-form-header">
                                <?php $this->html( 'header' ); ?>
                        </section>



			<div class="mw-ui-vform-field mw-submit">
				<?php
				echo Html::submitButton(
					$this->getMsg( $this->data['loggedin'] ? 'createacct-another-submit' : 'createacct-submit' ),
					array(
						'id' => 'wpCreateaccount',
						'name' => 'wpCreateaccount',
						'tabindex' => $tabIndex++
					),
					array(
						'mw-ui-block',
						'mw-ui-constructive',
					)
				);
				?>
			</div>
			<?php if ( $this->haveData( 'uselang' ) ) { ?><input type="hidden" name="uselang" value="<?php $this->text( 'uselang' ); ?>" /><?php } ?>
			<?php if ( $this->haveData( 'token' ) ) { ?><input type="hidden" name="wpCreateaccountToken" value="<?php $this->text( 'token' ); ?>" /><?php } ?>
		</form>
		<?php if ( !wfMessage( 'signupend' )->isDisabled() ) { ?>
			<div id="signupend"><?php $this->html( 'signupend' ); ?></div>
		<?php } ?>
	</div>
	<div class="mw-createacct-benefits-container">
		<h2><?php $this->msg( 'createacct-benefit-heading' ); ?></h2>
		<div class="mw-createacct-benefits-list">
			<?php
			for ( $benefitIdx = 1; $benefitIdx <= $this->data['benefitCount']; $benefitIdx++ ) {
				// Pass each benefit's head text (by default a number) as a parameter to the body's message for PLURAL handling.
				$headUnescaped = $this->getMsg( "createacct-benefit-head$benefitIdx" )->text();
			?>
				<div class="mw-number-text <?php $this->msg( "createacct-benefit-icon$benefitIdx" ); ?>">
					<h3><?php $this->msg( "createacct-benefit-head$benefitIdx" ); ?></h3>
					<p><?php echo $this->getMsg( "createacct-benefit-body$benefitIdx" )->params( $headUnescaped )->escaped(); ?></p>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php

	}
}
