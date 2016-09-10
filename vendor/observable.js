var observerable = {
	listeners: {},
	addListener: function(object, evt, callback) {
		if ( !this.listeners.hasOwnProperty(evt) ) {
			this.listeners[evt] = [];
		}
		this.listeners[evt].push(object[callback]);
	},
	removeListener: function(object, evt, callback) {
		if ( this.listeners.hasOwnProperty(evt) ) {
			var i,length;

			for (i = 0, length = this.listeners[evt].length; i < length; i += 1) {
				if( this.listeners[evt][i] === object[callback]) {
					this.listeners[evt].splice(i, 1);
				}
			}
		}
	},
	triggerEvent: function(evt, args) {
		if ( this.listeners.hasOwnProperty(evt) )    {
			var i,length;

			for (i = 0, length = this.listeners[evt].length; i < length; i += 1) {
				this.listeners[evt][i](args);
			}
		}
	}
};