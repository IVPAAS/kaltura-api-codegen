<% parameters.filter(function(param) {return param.fields}).forEach(function(param, index) { -%>
<% if (param.abstract) { -%>
$<%- param.name %> = new <%- '<\%- Lucy.answer("' + param.name + '[objectType]") || "' + param.class + '" %\>' %>();
<% } else { -%>
$<%- param.name %> = new <%- param.class %>();
<% } -%>
<%   param.fields.forEach(function(field) { -%>
<%    var answerName = param.name + '[' + field.name + ']'; -%>
<%    if (field.objectType) { -%>
<%- '<\% if (Lucy.answer("' + param.name + '[objectType]") === "' + field.objectType +'" && Lucy.answer("' + answerName + '") !== null) { -%\>' %>
<%    } else { -%>
<%- '<\% if (Lucy.answer("' + answerName + '") !== null) { -%\>' %>
<%    } -%>
<%     if (field.type.indexOf('Kaltura') === 0) { -%>
$<%- param.name %>-><%- field.name %> = new <%- '<\%- Lucy.answer("' + answerName + '") %\>' %>();
<%     } else if (!field.enum) { -%>
$<%- param.name %>-><%- field.name %> = <%- '<\%- Lucy.code.variable("answers.' + answerName + '") %\>' %>;
<%     } else { -%>
<%       for (valueName in field.enum.values) { -%>
<%- '<\% if (Lucy.answer("' + answerName + '") === ' + JSON.stringify(field.enum.values[valueName]) + ') { -%\>' %>
$<%- param.name %>-><%- field.name %> = <%- field.enum.name %>::<%- valueName %>;
<%- '<\% } -%\>' %>
<%       } -%>
<%     } -%>
<%- '<\% } -%\>' %>
<%   }); -%>
<% }); -%>
<% parameters.filter(function(param) {return !param.fields}).forEach(function(param, index) { -%>
<%     if (param.name === 'userId') { -%>
$<%- param.name %> = USER_ID;
<%     } else if (!param.enum) { %>
$<%- param.name %> = <%- '<\%- Lucy.code.variable("answers.' + param.name + '") %\>' %>;
<%     } else { -%>
<%       for (valueName in param.enum.values) { -%>
<%- '<\% if (Lucy.answer("' + param.name + '") === ' + JSON.stringify(param.enum.values[valueName]) + ') { -%\>' %>
$<%- param.name %> = <%- param.enum.name %>::<%- valueName %>;
<%- '<\% } -%\>' %>
<%       } -%>
<%     } -%>
<% }); -%>

try {
  $result = $client-><%- service %>-><%- action %>(<%- -%>
<% if (parameters.length === 0) { -%>
<%- ');' %>
<% } else if (parameters.length === 1) { -%>
$<%- parameters[0].name %>);
<% } else { -%>

<% parameters.forEach(function(param, index) { -%>
    $<%- param.name %><%- index < parameters.length -1 ? ', ' : ');' %>
<% }); -%>
<% } -%>
<% if (returns === 'list') { -%>
<%- '<\%- Lucy.returnCode("$result->objects", 2) %\>' %>
<% } else { -%>
<%- '<\%- Lucy.returnCode("$result", 2) %\>' %>
<% } -%>
} catch (Exception $e) {
  echo $e->getMessage();
}
