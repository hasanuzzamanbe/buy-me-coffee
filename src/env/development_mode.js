const glob = require('glob');
const fs = require('fs');

// For entry file selection
glob("buy-me-coffee.php", function(err, files) {
        files.forEach(function(item, index, array) {
            const data = fs.readFileSync(item, 'utf8');
            const mapObj = {
                WPM_BMC_PRODUCTION: "WPM_BMC_DEVELOPMENT"
            };

            const result = data.replace(/WPM_BMC_PRODUCTION/gi, function (matched) {
                return mapObj[matched];
            });

            fs.writeFile(item, result, 'utf8', function (err) {
            if (err) return console.log(err);
        });
        console.log('✅  Production asset enqueued!');
    });
});