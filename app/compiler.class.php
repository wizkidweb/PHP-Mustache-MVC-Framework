<?php

class Compiler {

	private $registry;

	private $scssc;

	function __construct($registry) {
		$this->registry = $registry;

		if (ENVIRONMENT == "development") {
			// Load SCSS compiler
			$this->scssc = new scssc();

			if ($this->registry->Config->template->compress_css) {
				$this->scssc->setFormatter("scss_formatter_compressed");
			}
		
			$this->scssc->addImportPath(function($path) {
				if (!file_exists(__SITE_PATH . "/views/_global/css/" . $path)) return null;
				return __SITE_PATH . "/views/_global/css/" . $path;
			});
		}
	}

	function compile_scss($dir, $file) {
		$file_arr = explode("/", $file);
		$filename = $file_arr[count($file_arr) - 1];

		if ($this->registry->Config->template->compress_css) {
			$new_filename = "_compile/" . str_replace(".scss", ".min.css", $filename);
		} else {
			$new_filename = "_compile/" . str_replace(".scss", ".css", $filename);
		}

		if (ENVIRONMENT == "development") {
			$scss = file_get_contents(__SITE_PATH . $file);
			$css = $this->scssc->compile($scss);

			$this->make_compile_dir($dir);

			if (file_put_contents(__SITE_PATH . $dir . "/" . $new_filename, $css)) {
				return $new_filename;
			}
		}

		return $new_filename;
	}

	function compile_js($dir, $file) {
		$file_arr = explode("/", $file);
		$filename = $file_arr[count($file_arr) - 1];

		if ($this->registry->Config->template->compress_js) {

			$new_filename = "_compile/" . str_replace(".js", ".min.js", $filename);

			if (ENVIRONMENT == "development") {
				$js = file_get_contents(__SITE_PATH . $file);

				$compressed_js = \JShrink\Minifier::minify($js);

				$this->make_compile_dir($dir);

				if (file_put_contents(__SITE_PATH . $dir . "/" . $new_filename, $compressed_js)) {
					return $new_filename;
				}
			}

			return $new_filename;
		} else {
			return $filename;
		}
	}

	private function make_compile_dir($dir) {
		if (!file_exists(__SITE_PATH . $dir . "/_compile/")) {
			mkdir(__SITE_PATH . $dir . "/_compile/");
		}
	}

}