//=============================================================================
// FILE:             PreProcess.java
//=============================================================================
// MODULE NAME:      USER TRACKING
// LAST CHANGED:     April 2011
// AUTHOR:           Matt Smith
// FILE DESCRIPTION: modify HTML/PHP site files for user tracking DB logging
//=============================================================================

/*

REMAINING TASKS:

(1)
work with ".htm" files as well as ".html" ...

*/

import java.io.*;

public class PreProcess
{

// *************************
// *** constants
// *************************
static final int TRACE_LEVEL = 1;

static final int SUFFIX_HTML = 0;
static final int SUFFIX_PHP = 1;

//Line of script to be inserted on top of every page
static final String PHP_TRACKING_DIRECTORY_NAME = "zz_TRACKER_FILES";
static final String PHP_TRACKING_DIRECTORY_PATH = "../" + PHP_TRACKING_DIRECTORY_NAME;
static final String PHP_TRACKING_FILE_NAME = "INCLUDE_userTracking.php";
static final String PHP_TRACKING_FILE_PATH = PHP_TRACKING_DIRECTORY_NAME + "/" + "INCLUDE_userTracking.php";

static final String PHP_INCLUDE_STRING = "<?php require_once('" + PHP_TRACKING_FILE_PATH + "'); ?>\n";

// *************************
// *** instance variables
// *************************

// folders in which to change files (relative to folder in which this application runs)
private String sourcePathName = "../../webSite";
private String outputPathName = "../../modifiedWebSite";

private File trackingFolderPathFile;

PrintStream p;
PrintStream q;

// *************************
// *** constructor
// ***********************
public PreProcess()
{
	// get current working directory
	File workingDirectory =   new File(System.getProperty("user.dir"));

	// create File objects for source / destination directories
	File sourcePathFile = new File( workingDirectory, sourcePathName );
	File outputPathFile = new File( workingDirectory, outputPathName );

	// get File object for tracking directory
	File trackingFolderSourcePathFile = new File( workingDirectory, PHP_TRACKING_DIRECTORY_PATH );
	File trackingFolderDestinationPathFile = new File( workingDirectory, outputPathName + "/" + PHP_TRACKING_DIRECTORY_NAME );


System.out.println("in constructor");

	trace("PreProcess", "-----------------------------------step (0)", 0);
	trace("PreProcess", "(0) delete contents of output directory", 0);
	// (0) delete contents of output directory
	deleteAllFiles( outputPathFile );

	trace("PreProcess", "-----------------------------------step (1)", 0);
	trace("PreProcess", "(1) copy all contents (including subdirectoies) from src to output directory", 0);

	// (1) copy all contents (including subdirectoies) from src to output directory
	copyDirectoryContents( sourcePathFile, outputPathFile );

	trace("PreProcess", "-----------------------------------step (2)", 0);
	trace("PreProcess", "(2) get list of '.html' files in this folder", 0);
	// (2) get list of ".html" files in this folder
	String[] htmlFileList = findFilesWithSuffix( outputPathFile, SUFFIX_HTML );

for(int i = 0; i < htmlFileList.length; i++)
{
	trace("PreProcess", "htmlFileList = " + htmlFileList[i], 0);
}

	trace("PreProcess", "-----------------------------------step (3)", 0);
	trace("PreProcess", "(3) rename all these files as '.php'", 0);
	
	// (3) rename all these files as ".php"
	renameHTMLtoPHP( outputPathFile, htmlFileList );

	trace("PreProcess", "-----------------------------------step (4)", 0);
	trace("PreProcess", "(4) get list of '.php' files in this folder", 0);
	
	// (4) get list of ".php" files in this folder

	String[] phpFileList = findFilesWithSuffix( outputPathFile, SUFFIX_PHP );

for(int i = 0; i < phpFileList.length; i++)
{
	trace("PreProcess", "phpFileList = " + phpFileList[i], 0);
}

	trace("PreProcess", "-----------------------------------step (5)", 0);
	trace("PreProcess", "(5) add PHP INCLUDE line in ALL PHP files in directory", 0);
	
	// (5) add PHP INCLUDE line in ALL PHP files in directory
	addTrackingCode( outputPathFile, phpFileList );

	trace("PreProcess", "-----------------------------------step (6)", 0);
	trace("PreProcess", "(6) replace any references to the old '.html' files to the renamed '.php' files", 0);
	
	// (6) replace any references to the old ".html" files to the renamed ".php" files
	replaceHTMLReferences( outputPathFile, phpFileList, htmlFileList );

	trace("PreProcess", "-----------------------------------step (7)", 0);
	trace("PreProcess", "(7) copy user tracking files into output directory", 0);
	
	// (7) copy the user tracking files (and their directory) into the output folder
	copyDirectory( trackingFolderSourcePathFile, trackingFolderDestinationPathFile );

	trace("PreProcess", "----------------------------------------------------------", 0);
	trace("PreProcess", "-------------------- end of application ------------------", 0);
	trace("PreProcess", "----------------------------------------------------------", 0);

} // constructor



// ************************
// *** deleteAllFiles() ***
// ************************
// delete all files in given file path
private boolean deleteAllFiles( File filePath )
{
	// flag for success or not
	boolean allFilesDeleted = true;

	// get list of all files in source directory
	String fileList[] = filePath.list();

	// copy each file
	for(int i = 0; i < fileList.length; i++)
	{
		// get current file name
		String currFileName = fileList[i];

		// get File object for path/filename
		File fileToDelete = new File( filePath, currFileName );

		boolean thisFileDeleted = deleteFileOrDirectory( fileToDelete );

		// if file not deleted, update flag that all files not sucessfully deleted
		if( !thisFileDeleted )
		{
			allFilesDeleted = false;
		} // if

	} // for

	return allFilesDeleted;

} // method

// ********************
// *** deleteFile() ***
// ********************
// delete given file
private boolean deleteFile( File fileToDelete )
{
	// flag for success or not
	boolean isDeleted = false;

	// delete the file!
	try
	{
		// delete file
		isDeleted = fileToDelete.delete();
	}
	catch (Exception e)
	{
		isDeleted = false;
		System.out.println("deleteFile() :: error during attempt to DELETE file");
	}

	return isDeleted;

} // method

// ********************
// *** deleteDir() ***
// ********************
// delete given file
private boolean deleteFileOrDirectory( File fileObject )
{
	// delete any contents
	if( fileObject.isDirectory() )
	{
		String[] children = fileObject.list();
		for( int i = 0; i < children.length; i++)
		{
			File childFile = new File( fileObject, children[i] );
			boolean success = deleteFileOrDirectory( childFile );

			if( !success )
			{
				System.out.println("deleteFileOrDirectory() :: error during attempt to DELETE file: " + fileObject.toString() );
				return false;

			} // if

		} // for

	} // if


	// delete dir
	return fileObject.delete();

} // method

// ***************************************
// *** copyDirectoryContents() ***
// ***************************************
// copy all files from source to output
private boolean copyDirectoryContents( File sourceFile, File outputFile )
{
	// flag for success or not
	boolean allFilesCopied = true;

	// get list of all files in source directory
	String fileList[] = sourceFile.list();

	// copy each file
	for(int i = 0; i < fileList.length; i++)
	{
		// get current file name
		String currItemName = fileList[i];
		File currItemFile = new File( sourceFile, currItemName);

		// if a directory, call directory copy method
		if( currItemFile.isDirectory() )
		{
			// copy this directory (and its contents etc.)
			String directoryName = currItemName;
			File sourceDirectory = currItemFile;
			File desinationDirectory = new File( outputFile, directoryName );

			trace("copyDirectoryContents", "** directory **  = " + currItemName, 0);

			// create destination dir if required, and copy all contents
			// ( RECURSION !)
			copyDirectory( sourceDirectory, desinationDirectory );

		} // if
		else
		{
			// must be a file - so use file copy

			trace("copyDirectoryContents", "fileList = " + currItemName, 0);

			// get references to source / desintation filepath+name
			File oldFile = currItemFile;
			File newFile = new File( outputFile, currItemName );

			// copy file contents ...
			boolean isCopiedSuccessfully = true;
			isCopiedSuccessfully = copyFile( oldFile, newFile );

			// if error then flag at least one file didn't copy
			if( !isCopiedSuccessfully )
			{
				allFilesCopied = false;
			} // if

		} // else

	} // for


	return allFilesCopied;

} // method


// ***********************
// *** copyDirectory() ***
// ***********************
// create directory if it doesn't already exist
// and copy contents to destination

private void copyDirectory( File sourceDirectory, File desinationDirectory )
{
	if( !desinationDirectory.exists() )
	{
		desinationDirectory.mkdirs();

	} // if

	// copy all contents of this directory to destination
	copyDirectoryContents( sourceDirectory, desinationDirectory );

} // method



// *****************************
// *** findFilesWithSuffix() ***
// *****************************
// return a list of files that end with given suffix (e.g. ".html", or ".php")
private String[] findFilesWithSuffix( File outputPathFile, int suffixType )
{
	/////////////////////////////////////////
	// Filter for php files
	FilenameFilter htmlFilter = new FilenameFilter()
	{
		public boolean accept(File dir, String name)
		{
			return name.endsWith( ".html" );
		} // method

	}; // filter


	FilenameFilter phpFilter = new FilenameFilter()
	{
		public boolean accept(File dir, String name)
		{
			return name.endsWith( ".php" );
		} // method

	}; // filter

	// get file list
	//File workingDirectory =   new File(System.getProperty("user.dir"));
	File workingDirectory =   outputPathFile;

	// get String array of SUFFIX (.html / .php etc.) filenames
	String[] fileList;

	switch( suffixType )
	{
		// search for ".html" files
		case SUFFIX_HTML:
			fileList = workingDirectory.list( htmlFilter );
			break;


		// search for ".php" files
		case SUFFIX_PHP:
		default:
			fileList = workingDirectory.list( phpFilter );
			break;

	} // switch

	return fileList;

} // method


// *************************
// *** renameHTMLtoPHP() ***
// *************************
// rename all the HTML files to end in ".php"
private boolean renameHTMLtoPHP( File outputPathFile, String []htmlFileList )
{
	// flag to indicate if all went well
	boolean allSucessfullyRenamed = true;

	// loop for each HTML file
	for(int i = 0; i < htmlFileList.length; i++)
	{
		// get current HTML file name
		String htmlFileName = htmlFileList[i];

		// create String for .php filename
		String phpFileName = htmlFileName.replace( ".html", ".php" );

trace("renameHTMLtoPHP", "htmlFileName = '" + htmlFileName + "', phpFileName = '" + phpFileName + "'", 0);


		// get current FILE object
		File htmlFile = new File( outputPathFile, htmlFileName );

		// get reference file with new (.php) name
		File phpFile = new File( outputPathFile, phpFileName );

		// rename file
		boolean isRenamed = renameFile( htmlFile, phpFile );

		if( !isRenamed )
		{
			// flag that at least one file didn't rename sucessfully
			allSucessfullyRenamed = false;

		} // if

	} // for

	return allSucessfullyRenamed;

} // method


// *************************
// *** addTrackingCode() ***
// *************************
// for given path/file list, insert tracking text to top of each file
private boolean addTrackingCode( File outputPathFile, String []phpFileList )
{
	// flag to indicate if all went well
	boolean allSucessfullyModified = true;

	// loop for each HTML file
	for(int i = 0; i < phpFileList.length; i++)
	{
		// get current HTML file name
		String phpFileName = phpFileList[i];

		// add text to top of file
		boolean isModified = insertFileFirstLine(outputPathFile, phpFileName);

		if( !isModified )
		{
			allSucessfullyModified = false;

		} // if

	} // for


	return allSucessfullyModified;

} // method


// *************************
// *** replaceHTMLReferences() ***
// *************************
// for given path/file list, insert tracking text to top of each file
private boolean replaceHTMLReferences( File outputPathFile, String []phpFileList, String []htmlFileList )
{
	// flag to indicate if all went well
	boolean allSucessfullyModified = true;

	// loop for each HTML file
	for(int i = 0; i < phpFileList.length; i++)
	{
		// get current HTML file name
		String phpFileName = phpFileList[i];

		// add text to top of file
		boolean isModified = replaceHTMLReferencesInFile(outputPathFile, phpFileName, htmlFileList );

		if( !isModified )
		{
			allSucessfullyModified = false;

		} // if

	} // for


	return allSucessfullyModified;

} // method


// *****************************
// *** insertFileFirstLine() ***
// *****************************
// insert the given string into the top of the given file
private boolean insertFileFirstLine( File path, String sourceFileName)
{
	// flag for sucess or not
	boolean isInserted = false;

	// set up file varibales
	File sourceFile = new File( path, sourceFileName );
	String tempFileName = "_temp_temp_1234.temp";
	File tempFile = new File( path, tempFileName );

	try
	{
		FileInputStream inputFile = new FileInputStream( sourceFile );

		//output to a tempoary text file
		FileOutputStream outputFile = new FileOutputStream( tempFile );

		DataInputStream dis = new DataInputStream(inputFile);
		PrintStream ps = new PrintStream( outputFile );

		// write speical PHP line as first line in file
		ps.println( PHP_INCLUDE_STRING );

		// copy everything from PHP file
		while (dis.available() !=0)
		{
			String line = dis.readLine();
			ps.println( line );

		} // while

		// close streams
		dis.close();
		ps.close();

		// if got this far, assume success
		isInserted = true;
	}
	catch (Exception e)
	{
		isInserted = false;
		System.out.println("insertFileFirstLine() :: error during attempt to copy text file");
	}

	// delete original file and rename temp to original
	boolean isDeleted = deleteFile( sourceFile );
	boolean isRenamed = renameFile( tempFile, sourceFile );

	if( !isDeleted || !isRenamed )
	{
		isInserted = false;

	} // if

	// return flag
	return isInserted;


} // method


// *************************************
// *** replaceHTMLReferencesInFile() ***
// *************************************
// for the given fileName replace any of the html file names with their .php equivalents
private boolean replaceHTMLReferencesInFile( File path, String sourceFileName, String []htmlFileList )
{
	// flag for sucess or not
	boolean allReplaced = false;

	// set up file varibales
	File sourceFile = new File( path, sourceFileName );
	String tempFileName = "_temp_temp_12345.temp";
	File tempFile = new File( path, tempFileName );

	try
	{
		FileInputStream inputFile = new FileInputStream( sourceFile );

		//output to a tempoary text file
		FileOutputStream outputFile = new FileOutputStream( tempFile );

		DataInputStream dis = new DataInputStream(inputFile);
		PrintStream ps = new PrintStream( outputFile );

		// copy everything from PHP file
		while (dis.available() !=0)
		{
			// get line from source file
			String sourceLine = dis.readLine();

			// replace any occurance of html file name with PHP equivalent
			// loop for each HTML file
			for(int i = 0; i < htmlFileList.length; i++)
			{
				// get current HTML file name
				String htmlFileName = htmlFileList[i];

				// create String for equivalent .php filename
				String phpFileName = htmlFileName.replace( ".html", ".php" );

				// add text to top of file
				sourceLine = sourceLine.replaceAll( htmlFileName, phpFileName );

			} // for


			// write converted line to temp file
			ps.println( sourceLine );

		} // while

		// close streams
		dis.close();
		ps.close();

		// if got this far, assume success
		allReplaced = true;
	}
	catch (Exception e)
	{
		allReplaced = false;
		System.out.println("replaceHTMLReferencesInFile() :: error during attempt to copy text file");
	}

	// delete original file and rename temp to original
	boolean isDeleted = deleteFile( sourceFile );
	boolean isRenamed = renameFile( tempFile, sourceFile );

	if( !isDeleted || !isRenamed )
	{
		allReplaced = false;

	} // if

	// return flag
	return allReplaced;


} // method


// *************************
// *** copyFile() ***
// *************************
// rename all the HTML files to end in ".php"
private boolean copyFile( File source, File destination )
{
	// flag for sucess or not
	boolean isCopied = false;

	try
	{

		// create I/O streams
		FileInputStream fis = new FileInputStream( source );
		FileOutputStream fos = new FileOutputStream( destination );

		byte buf[] = new byte[1024];

		int i = 0;
		// while not end of file copy bytes ...
		while( (i=fis.read(buf)) != -1 )
		{
			fos.write( buf, 0, i );

		} // while

		// close streams
		fis.close();
		fos.close();

		// if we get this far without an exception we assume file copied okay
		isCopied = true;
	}
	catch (Exception e)
	{
		isCopied = false;
		System.out.println("copyFile() :: error during attempt to copy file");
	}

	return isCopied;

} // method


// *************************
// *** renameFile() ***
// *************************
// rename all the HTML files to end in ".php"
private boolean renameFile( File source, File destination )
{
	// flag for sucess or not
	boolean isRenamed = false;

	// (1) copy the file
	isRenamed = copyFile( source, destination );

	// (2) delete the original (if copied sucessfully
	if( isRenamed )
	{
		// delete file
		boolean isDeleted = deleteFile( source );

		// reset flag if delete unsucessful
		if( !isDeleted )
		{
			isRenamed = false;
		} // if

	} // if

	return isRenamed;

} // method


// *************************
// *** trace()
// ***********************
// TRACE_LEVEL 0 = no trace
// TRACE_LEVEL 1 = brief
// TRACE_LEVEL 2 = verbose
//
public void trace( String callingMethod,  String msg, int msgNumber )
{
//System.out.println("in TRACE");
	// TRACE_LEVEL 1 = brief
	if( TRACE_LEVEL == 1 )
	{
		System.out.println( msg );
	} // if

	// TRACE_LEVEL 2 = verbose
	if( TRACE_LEVEL == 2 )
	{
		// pre-fix message number if msgNumber > 0
		if( msgNumber > 0 )
		{
			msg += "(" + msgNumber + ") " + msg;
		} // if

		System.out.println( "TRACE :: " + callingMethod + "() :: " + msg );
	} // if

} // method

// *************************
// *** main()
// ***********************
public static void main(String[] args)
{
	System.out.println("main() :: start application");
	PreProcess application = new PreProcess();

	System.out.println("main() :: exit application");
} // main



} // class
