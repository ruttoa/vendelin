"use strict";

/* Load Gulp and plugins */
const { series, parallel, watch, src, dest } = require("gulp"),
	concat = require("gulp-concat"),
	sass = require("gulp-sass"),
	sourcemaps = require("gulp-sourcemaps"),
	autoprefixer = require("gulp-autoprefixer"),
	uglify = require("gulp-uglify"),
	log = require("fancy-log"),
	del = require("del"),
	browserSync = require("browser-sync").create(),
	wpPot = require('gulp-wp-pot'),

	siteURL = "vendelin.localhost", // for browser-sync
	textDomain = "vendelin", // for translations

	/** JS source files to concatenate and uglify */
	uglifySrc = [
		"js/scripts.js"
	],
	/** CSS source files to concatenate and minify */
	cssminSrc = [
		"css/style.scss"
	];


/** Styles - SASS, autoprefix & minify */
function styles() {
	return src(cssminSrc)
		.pipe(sourcemaps.init({ loadMaps: true })) // Loads existing sourcemaps too
		.pipe(concat("style.min.scss"))
		.pipe(sass({
			outputStyle: 'compressed' // minifies style.min.css
		}).on('error', sass.logError))
		.pipe(autoprefixer({
			overrideBrowserslist: [ // https://github.com/ai/browserslist#queries
				'last 2 versions',
				'> 5%',
				'ie >= 9',
				'ie_mob >= 10',
				'ff >= 30',
				'chrome >= 34',
				'safari >= 7',
				'opera >= 23',
				'ios >= 7',
				'android >= 4',
				'bb >= 10'
			]
		}))
		.pipe(sourcemaps.write('/', { // write style.min.css.map to same directory as style.min.css
			includeContent: false,
			sourceRoot: '../css' // relative to minified output location
		}))
		.pipe(dest("dist"))
		.pipe(browserSync.stream());
};
function styles_editor() {
	return src(["css/editor-style.scss"])
		.pipe(concat("editor-style.scss"))
		.pipe(sass({
			outputStyle: 'compressed' // minify
		}).on('error', sass.logError))
		.pipe(autoprefixer({
			overrideBrowserslist: [ // https://github.com/ai/browserslist#queries
				'last 2 versions',
				'> 5%',
				'ie >= 9',
				'ie_mob >= 10',
				'ff >= 30',
				'chrome >= 34',
				'safari >= 7',
				'opera >= 23',
				'ios >= 7',
				'android >= 4',
				'bb >= 10'
			]
		}))
		.pipe(dest("dist"))
		.pipe(browserSync.stream());
};

/** Uglify */
function scripts() {
	return src(uglifySrc)
		.pipe(concat("scripts.min.js"))
		.pipe(uglify())
		.pipe(dest("dist"))
		.pipe(browserSync.stream());
};

/** Translate - Creates theme .pot-file */
function translate() {
	return src(["**/*.php"])
		.pipe(wpPot({
			domain: textDomain,
			package: textDomain,
		}))
		.pipe(dest('./languages/' + textDomain + '.pot'));
};

/** Browser-sync */
function browser_sync() {
	browserSync.init({
		proxy: siteURL,
	});
};

function watchForChanges() {

	log("Watching changes...");

	/** Watch for JS files */
	watch([
		"js/lib/*.js",
		"js/*.js"
	], scripts);

	/** Watch for SASS */
	watch([
		"css/lib/*.css",
		"css/sass-partials/*.scss",
		"css/style.scss"
	], styles);

	/** Watch for editor styles */
	watch([
		"css/sass-partials/*.scss",
		"css/editor-style.scss"
	], styles_editor);
};

/** Clean */
function clean() {
	return del([".tmp", "dist"]);
};


exports.watch = series(parallel(scripts, styles, styles_editor), watchForChanges);
exports.build = series(clean, parallel(styles, scripts, styles_editor));
exports.styles = styles;
exports.scripts = scripts;
exports.styles_editor = styles_editor;
exports.browsersync = browser_sync;
exports.translate = translate;
exports.clean = clean;
exports.default = exports.watch;