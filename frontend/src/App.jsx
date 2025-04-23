import Editor from "@monaco-editor/react";

const App = function() {

  return (
    <>
      <Editor
        height="100vh"
        defaultLanguage="javascript"
        onChange={(value) => setCode(value)}
        theme="vs-dark"
      />
    </>
  );
}

export default App;