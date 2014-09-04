sqmc
====

sqmc is a compiler that converts files written in the SQM language to PHP scripts.

The SQM language allows you to easily create small PHP sites, without writing all that code starting with "mysql_".

Moreover, you can still use PHP code in your script where you need it.

Syntax
------

### Commands tags
These can have the following syntax:
`%{command}`
`%{command parameter}`
`%{command:name parameter}`

### Result tags
These currently only can be used within a `%{q}` command. They look like:
`%[result_name]`
`%[result_name:query_name]`

Commands
--------

### `%{q:query_name sql_query} ... %{/q}`
This executes the *sql_query* SQL command.
The results can be iterated through between `%{q ...}` and the closing `%{/q}` element.
Only one SQL command can be executed at once.
Example:

	<table>
	%{q SELECT * FROM my_table}
		<tr>
			<td>%[id]</td>
			<td>%[name]</td>
			<td>%[anything]</td>
		</tr>
	%{/q}
	</table>

The *query_name* parameter can be useful if a query is used within another query. This way, we can select which query do the result tags correspond to.

	<table>
	%{q:mainquery SELECT * FROM my_table;}
		<tr>
			<td>%[mainquery:id]</td>
			<td>%[mainquery:name]</td>
			<td>%[mainquery:param3]</td>
			<td><ul>
				%{q:subquery SELECT something FROM second_table WHERE id=%[mainquery:id];}
					<li>%[subquery:something]</li>
				%{/q:subquery}
			</ul></td>
		</tr>
	%{/q:mainquery}
	</table>

### `%{qr:query_name sql_query}`
This executes the *sql_query* SQL command and pastes its output right there.
Example:

	<span>The number of receivers is %{qr SELECT COUNT(*) FROM receivers;}.</span>

### `%{qn sql_query}`
This executes the *sql_query* SQL command with no output. (Goes to */dev/null*, hah :-))

### `%{init}`
This includes necessary PHP files for the SQM engine, and should be called at the beginning of every SQM file.

### `%{delimit sql_query}`

*To be continued...*

