const glob = require('glob');
const fs = require('fs');

// For entry file selection
glob.glob("buy-me-coffee.php", function(err, files) {
    if (err) {
        console.error('Error finding files:', err);
        return;
    }

    files.forEach(function(item) {
        // Read the file synchronously
        fs.readFile(item, 'utf8', (err, data) => {
            if (err) {
                console.error('Error reading file:', err);
                return;
            }

            // Define the mapping of the terms to replace
            const mapObj = {
                BUYMECOFFEE_PRODUCTION: "BUYMECOFFEE_DEVELOPMENT"
            };

            // Replace occurrences in the file's content
            const result = data.replace(/BUYMECOFFEE_PRODUCTION/gi, function(matched) {
                return mapObj[matched];
            });

            // Write the changes back to the same file
            fs.writeFile(item, result, 'utf8', function(err) {
                if (err) {
                    console.error('Error writing file:', err);
                } else {
                    console.log('✅  Development asset enqueued in', item);
                }
            });
        });
    });
});