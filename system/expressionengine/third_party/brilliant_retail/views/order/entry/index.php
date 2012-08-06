<?php
	$this->table->set_template($cp_table_template);
   	$this->table->set_heading(
                lang('updated_sites_config_name').'/'.lang('edit'),
                lang('view_pings'),
                lang('updated_sites_config_url'),
                form_checkbox('select_all', 'true', FALSE, 'class="toggle_all" id="select_all"').NBS.lang('delete', 'select_all')
        );

        $base_url = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=updated_sites'.AMP;

       $this->table->add_row(
                        'a',
                        'a',
                        'a',
                        'a'
                        );
        
echo $this->table->generate();
