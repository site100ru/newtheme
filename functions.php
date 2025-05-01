<?php
	add_filter('pre_set_site_transient_update_themes', 'check_custom_theme_updates');

	function check_custom_theme_updates($transient) {
		$theme_slug = 'newtheme'; // Имя папки темы
		$current_version = wp_get_theme()->get('Version');
		$update_server = 'https://site100theme.ru/wp-content/themes/newtheme/theme-updates.json';

		// Запрос к вашему серверу
		$response = wp_remote_get($update_server);

		if (!is_wp_error($response)) {
			$update_data = json_decode(wp_remote_retrieve_body($response));

			if (
				$update_data &&
				version_compare($current_version, $update_data->version, '<') &&
				!empty($update_data->download_url)
			) {
				$transient->response[$theme_slug] = array(
					'theme'       => $theme_slug,
					'new_version' => $update_data->version,
					'package'     => $update_data->download_url,
					'url'         => 'https://newtheme.site/wp-content/themes/newtheme/changelog' // Опционально
				);
			}
		}
		
		return $transient;
	}
?>