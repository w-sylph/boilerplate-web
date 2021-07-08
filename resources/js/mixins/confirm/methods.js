/* eslint-disable vue/require-name-property */
// @vue/component
export default {
	computed: {
		dialogTitle() {
			return this.title;
		},

		dialogMessage() {
			return this.message;
		}
	},

	methods: {
		showConfirm(event) {
			if (!this.confirmDialog) {
				this.onDialogSuccess(event);
				return;
			}

			let message = {
				title: this.dialogTitle,
				body: this.dialogMessage
			};

			let options = {
				loader: true,
				okText: this.okText,
				cancelText: this.cancelText,
				animation: 'fade',
				type: this.dialogType,
				verification: this.verification,
				verificationHelp: this.verificationHelp
			};

			this.$dialog.confirm(message, options)
			.then((dialog) => {
				this.onDialogSuccess(event, dialog);
			}).catch(() => {
				this.onDialogCancel(event);
			});
		},

		onDialogSuccess(event, dialog) {
			console.log(event, dialog);
		},

		onDialogCancel(event) {
			console.log(event);
		}
	}
};