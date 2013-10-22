var adrrDataGetter = angular.module('adrrDataGetter', [], null).factory
(
	'adrrDataGetter', function ($http) {

		var targets = [];

		var timers = [];

		//getData is the function that actually fetches data. It is used by function set.
		//  sourceUrl: the url to the requested data source
		//  target: is an array object. It has to be initialized as an empty array before calling getData.
		//  method: post or get.
		//  args: any additional paramitures to be passed with the request.
		var getData = function (sourceUrl, target, method, args) {

			$http
			({
				 method: (method !== undefined ? method : 'GET'),
				 url: sourceUrl,
				 data: (args !== undefined ? args : null)
			 }).success
			(
				function (data) {
					var targetIndex = _.indexOf(targets, target, 0);

					if (angular.isArray(data)) {
						var dataLength = data.length;

						for (var i = 0; i < dataLength; i++) {

							if (_.indexOf(targets[targetIndex], data[i], 0) !== -1) {
								targets[targetIndex].push(data[i]);
							}
						}

						dataLength = targets[targetIndex].length;

						for (i = 0; i < dataLength; i++) {

							if (_.indexOf(data, targets[targetIndex][i], 0) !== -1) {
								targets[targetIndex].splice(i, 1);
							}
						}
					}
				}
			)
		};

		var set = function (sourceUrl, target, time, method, args) {

			if (time !== undefined) {
				var timer = window.setInterval(getData.bind(sourceUrl, target, method, args), time);

				timers.push(timer);

				targets.push(target);
			}
		};

		var unset = function (target) {

			var targetIndex = _.indexOf(targets, target, 0);

			window.clearInterval(timers[targetIndex]);

			targets.splice(targetIndex, 1);
		};

		return {
			set: set,
			unset: unset
		}
	}
);