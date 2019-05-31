{*-------------------------------------------------------+
| SYSTOPIA Status Tracker Extension                      |
| Copyright (C) 2019 SYSTOPIA                            |
| Author: B. Endres (endres@systopia.de)                 |
| http://www.systopia.de/                                |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+-------------------------------------------------------*}

<div><p>{ts domain="de.systopia.statustracker"}This page lists all projects/processes of which you are the lead of.{/ts}</p></div>

{*<div>
{if $processlist}
    <p>{ts domain="de.systopia.statustracker" 1=$active_count 2=$inactive_count}%1 active processes found, %2 inactive.{/ts}</p>
{else}
    <p>{ts domain="de.systopia.statustracker"}No processes found.{/ts}</p>
{/if}
</div>*}

{if $processlist}
<table class="statustracker-table">
    <thead>
        <tr>
            <th>{ts domain="de.systopia.statustracker"}Category{/ts}</th>
            <th>{ts domain="de.systopia.statustracker"}Status{/ts}</th>
            <th>{ts domain="de.systopia.statustracker"}Contact{/ts}</th>
            <th>{ts domain="de.systopia.statustracker"}Title{/ts}</th>
            <th>{ts domain="de.systopia.statustracker"}Last Change{/ts}</th>
            <th>{ts domain="de.systopia.statustracker"}Info{/ts}</th>
        </tr>
    </thead>
    <tbody>
    {foreach from=$processlist item=process}
        <tr>
            <td>{$process.category}</td>
            <td>{$process.status}</td>
            <td>{$process.contact_image}<a href="{$process.contact_link}">{$process.contact_name}</a></td>
            <td>{$process.title}</td>
            <td>{$process.change_date|crmDate:$config->dateformatFull}</td>
            <td>
                {if $process.link}<a href="{$process.link}" title="{$process.link}">{ts domain="de.systopia.statustracker"}LINK{/ts}</a>{/if}
                &nbsp;
                {if $process.note}<span title="{$process.note}">{ts domain="de.systopia.statustracker"}NOTE{/ts}</span>{/if}
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
{/if}