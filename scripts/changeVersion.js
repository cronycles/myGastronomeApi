const composerFile = require('../composer.json');
const fs = require('fs');

var versionType = process.argv.slice(2);

if (versionType == "mayor") {
    console.log("changing mayor version");
    return increaseProjectVersion(true);
} else if (versionType == "minor") {
    console.log("changing minor version");
    return increaseProjectVersion();
} else {
    console.log("cannot understand version type, the version will not be changed")
}

function increaseProjectVersion(isMayor) {
    let versionObject = getCurrentProjectVersionObject();
    if (isMayor) {
        versionObject.mayor++;
    } else {
        versionObject.minor++;
    }
    let newVersion = versionObject.mayor + "." + versionObject.minor;

    return changeProjectVersion(newVersion);
}

function getCurrentProjectVersionObject() {
    let version = composerFile.version;
    let myRegexp = /(\d+).(\d+)/g;
    let match = myRegexp.exec(version);

    let mayor = parseInt(match[1]);
    let minor = parseInt(match[2]);

    return {
        mayor: mayor,
        minor: minor
    }
}

function changeProjectVersion(newVersion) {
    setConfigFileVersion(newVersion);
    return changeComposerFileVersion(newVersion);
}

function setConfigFileVersion(newVersion) {
    var fileName = "./config/app.php";
    fs.readFile(fileName, 'utf8', function (err, data) {
        if (err) {
            return console.log(err);
        }
        var result = data.replace(/('version'\s*=>\s*')(.*)(',)/g, "$1" + newVersion + "$3");

        fs.writeFile(fileName, result, 'utf8', function (err) {
            if (err) return console.log(err);
        });
    });
}

function changeComposerFileVersion(newVersion) {
    var fileName = "./composer.json";
    fs.readFile(fileName, 'utf8', function (err, data) {
        if (err) {
            return console.log(err);
        }
        var result = data.replace(/("version"\s*:\s*")(.*)(")/g, "$1" + newVersion + "$3");

        fs.writeFile(fileName, result, 'utf8', function (err) {
            if (err) return console.log(err);
        });
    });
}
